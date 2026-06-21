<?php

namespace App\Http\Controllers;

use App\Constants\PrescriptionOptions;
use App\Models\Category;
use App\Models\Product;
use App\Services\SpeedyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ShopController extends Controller
{
    /** Show all products */
    public function index()
    {
        return view('Frontend.shop.Shop', [
            'products'       => $this->productsQuery()->paginate(15),
            'category'       => null,
            'categoriesTree' => $this->buildCategoriesTree(),
        ]);
    }

    /** Show the checkout */
    public function checkout () {

        $products = Session::get('products');

        if(!$products || count($products)  < 0) {
            return redirect(route('cart'));
        }

        // dd($products);

       $subtotal = 0;

        foreach ($products as $product) {
            $subtotal += $product['final_price'] * $product['quantity'];
        }

        return view('Frontend.shop.Checkout', [
            'speedyOffices' => SpeedyService::offices(),
            'products' => $products,
            'subtotal' => $subtotal
        ]);
    }

    /** Show the cart */
    public function cart()
    {
        $sessionProducts = Session::get('products', []);
        $productIds = array_keys($sessionProducts);

        $databaseProducts = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $products = [];

        foreach ($sessionProducts as $productId => $cartProduct) {
            $product = $databaseProducts->get((int) $productId);

            if (! $product) {
                continue;
            }

            $price = (float) $product->price;
            $discount = (int) $product->discount;

            $finalPrice = $discount > 0
                ? $price - (($price * $discount) / 100)
                : $price;

            $products[$productId] = array_merge($cartProduct, [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $price,
                'discount' => $discount,
                'final_price' => round($finalPrice, 2),
                'image' => $product->main_image,
            ]);
        }

        $subtotal = 0;

        foreach ($products as $product) {
            $subtotal += $product['final_price'] * $product['quantity'];
        }

        return view('Frontend.shop.Cart', [
            'products' => $products,
            'subtotal' => $subtotal,
        ]);
    }
    /** Show products from a specific category (including descendants)
     *
     * @param string $category_slug
     * @return View
     */
    public function category(string $category_slug)
    {
        $category    = Category::where('slug', $category_slug)->firstOrFail();
        $categoryIds = $this->getCategoryIds($category);

        $products = $this->productsQuery()
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->paginate(15);

        return view('Frontend.shop.Shop', [
            'products'       => $products,
            'category'       => $category,
            'categoriesTree' => $this->buildCategoriesTree(),
        ]);
    }



    /** Show a specific product
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug)
    {
        // Session::invalidate();
        // dd(Session::get('products'));

        $product = Product::with(['categories', 'attributeValues.type'])
            ->where('slug', $slug)
            ->first();

        if (! $product) {
            return view('errors.ProductNotFound');
        }

        return view('Frontend.shop.Show', [
            'product' => $product,
            'sphValues' => PrescriptionOptions::SPH,
            'cylValues' => PrescriptionOptions::CYL,
            'addValues' => PrescriptionOptions::ADD,
            'axisValues' => PrescriptionOptions::AXIS,
        ]);
    }


    /** Return all ids of a given category
     *
     * @param Collection $category
     * @return array[]
     */
    private function getCategoryIds($category)
    {
        $ids = [$category->id];

        if ($category->children) {
            foreach ($category->children as $child) {
                $ids = array_merge($ids, $this->getCategoryIds($child));
            }
        }

        return $ids;
    }

    /** Shared base query — eager loads + ordering */
    private function productsQuery()
    {
        return Product::with(['categories', 'attributeValues'])->latest();
    }

    /** Build the categories tree from root nodes (recursive via Category->children) */
    private function buildCategoriesTree(): array
    {
        $roots = Category::whereNull('category_parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return $this->buildTree($roots);
    }

    /** Recursive helper used by buildCategoriesTree
     *
     * @param Collection $categories
     * @return array[]
     */
    private function buildTree($categories): array
    {
        $tree = [];

        foreach ($categories as $category) {
            $tree[] = [
                'id'       => $category->id,
                'name'     => $category->name,
                'slug'     => $category->slug,
                'children' => $this->buildTree($category->children),
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
    private function flattenCategoryTree($categories, $parentPath = ''): array
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
                $tree = array_merge($tree, $this->flattenCategoryTree($category->children, $currentPath));
            }
        }

        return $tree;
    }
}
