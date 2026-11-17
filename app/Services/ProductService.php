<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductService
{

    /**
     * Apply product attribute and price filters to the given query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public static function filteredProducts(Builder $query, Request $request): Builder
    {
        $filters = $request->except(['page', 'price-range']);

        foreach ($filters as $attributeTypeSlug => $attributeValueSlug) {
            $query->whereHas('attributeValues', function ($query) use ($attributeTypeSlug, $attributeValueSlug) {
                $query->where('slug', $attributeValueSlug)->whereHas('type', function ($query) use ($attributeTypeSlug) {
                    $query->where('slug', $attributeTypeSlug);
                });
            });
        }

        if ($request->filled('price-range')) {
            $query->whereBetween('price', [0, $request->integer('price-range')]);
        }

        return $query;
    }

    /**
     * Check whether the product belongs to the sunglasses category
     * or any of its subcategories.
     *
     * @param Product $product
     * @return bool
     */
    public static function isProductSunglasses(Product $product): bool
    {
        $categoriesTree = Cache::remember(
            'product_categories_tree',
            now()->addHours(24),
            fn() => self::buildCategoriesTree()
        );

        $sunglassesCategory = Category::where('slug', 'slunchevi-ochila')->first();

        if (!$sunglassesCategory) {
            return false;
        }

        $sunglassesCategoryIds = self::getCategoryIds(
            $categoriesTree,
            $sunglassesCategory->id
        );

        return $product->categories()
            ->whereIn('categories.id', $sunglassesCategoryIds)
            ->exists();
    }

    /**
     * Get the IDs of a category and all its descendants.
     *
     * @param array $categoriesTree
     * @param int $categoryId
     * @return array
     */
    public static function getCategoryIds(array $categoriesTree, int $categoryId): array
    {
        foreach ($categoriesTree as $category) {

            if ($category['id'] === $categoryId) {
                return self::collectCategoryIds($category);
            }

            $ids = self::getCategoryIds($category['children'], $categoryId);

            if (!empty($ids)) {
                return $ids;
            }
        }

        return [];
    }

    /**
     * Recursively collect category IDs without database queries.
     */
    private static function collectCategoryIds(array $category): array
    {
        $ids = [$category['id']];

        foreach ($category['children'] as $child) {
            $ids = array_merge($ids, self::collectCategoryIds($child));
        }

        return $ids;
    }

    /** Shared base query — eager loads + ordering */
    public static function productsQuery()
    {
        return Product::with(['categories', 'attributeValues'])->latest();
    }

    /** Build the categories tree from root nodes (recursive via Category->children) */
    public static function buildCategoriesTree(): array
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category_parent_id']);

        $grouped = $categories->groupBy('category_parent_id');

        return self::buildTree($grouped, null);
    }

    /**
     * Recursively build the category hierarchy in memory.
     *
     * @param Collection $grouped
     * @param int|null $parentId
     * @return array
     */
    private static function buildTree(Collection $grouped, ?int $parentId): array
    {
        $tree = [];

        foreach ($grouped->get($parentId, collect()) as $category) {
            $tree[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'children' => self::buildTree($grouped, $category->id),
            ];
        }

        return $tree;
    }

    /** Return the category tree
     *
     * @param Collection $categories
     * @param string $parentPath
     * @return array[]
     */
    public static function flattenCategoryTree($categories, $parentPath = ''): array
    {
        $tree = [];

        foreach ($categories as $category) {

            $currentPath = $parentPath ? $parentPath . ' → ' . $category->name : $category->name;

            $tree[] = [
                'id' => $category->id,
                'name' => $category->name,
                'path' => $currentPath,
            ];

            if ($category->children && $category->children->count()) {
                $tree = array_merge($tree, self::flattenCategoryTree($category->children, $currentPath));
            }
        }

        return $tree;
    }

    /**
     * Find the breadcrumb path for a category inside the category tree.
     *
     * @param array $categoriesTree
     * @param string $activeSlug
     * @return array
     */
    public static function getCategoryBreadcrumbs(array $categoriesTree, string $activeSlug): array
    {

        foreach ($categoriesTree as $category) {

            if ($category['slug'] === $activeSlug) {
                return [[
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                ]];
            }

            if (! empty($category['children'])) {
                $childPath = self::getCategoryBreadcrumbs(
                    $category['children'],
                    $activeSlug
                );

                if (! empty($childPath)) {
                    return array_merge(
                        [[
                            'name' => $category['name'],
                            'slug' => $category['slug'],
                        ]],
                        $childPath
                    );
                }
            }
        }

        return [];
    }
}
