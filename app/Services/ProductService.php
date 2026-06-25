<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;


class ProductService
{
    /** Get the categories of a specific product
     *
     * @param Product $product
     * @return boolean
     */
    public static function getProductTree(Product $product): bool
    {
        $categoryIds = [];

        foreach ($product->categories as $category) {
            $categoryIds = array_merge(
                $categoryIds,
                self::getCategoryIds($category)
            );
        }

        return Category::whereIn('id', array_unique($categoryIds))
            ->where('slug', 'dioptricni-ramki')
            ->exists();
    }

    /** Return all ids of a given category
     *
     * @param Collection $category
     * @return array[]
     */
    public static function getCategoryIds($category)
    {
        $ids = [$category->id];

        if ($category->children) {
            foreach ($category->children as $child) {
                $ids = array_merge($ids, self::getCategoryIds($child));
            }
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
        $roots = Category::whereNull('category_parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return self::buildTree($roots);
    }

    /** Recursive helper used by buildCategoriesTree
     *
     * @param Collection $categories
     * @return array[]
     */
    private static function buildTree($categories): array
    {
        $tree = [];

        foreach ($categories as $category) {
            $tree[] = [
                'id'       => $category->id,
                'name'     => $category->name,
                'slug'     => $category->slug,
                'children' => self::buildTree($category->children),
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
}
