<?php

namespace App\Http\Controllers;

use App\Constants\PrescriptionOptions;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\SpeedyService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;


class ShopController extends Controller
{
    /** Show all products */
    public function index()
    {
        return view('Frontend.shop.Shop', [
            'products'       => Product::with(['categories', 'attributeValues'])->paginate(15),
            'category'       => null,
            'categoriesTree' => ProductService::buildCategoriesTree(),
        ]);
    }

    /** Show the checkout */
    public function checkout()
    {
        $products = Session::get('products');

        if (! $products || count($products) <= 0) {
            return redirect(route('cart'));
        }

        $subtotal = 0;

        foreach ($products as $product) {
            $subtotal += $product['final_price'] * $product['quantity'];
        }

        return view('Frontend.shop.Checkout', [
            'speedyOffices' => SpeedyService::offices(),
            'products'      => $products,
            'subtotal'      => $subtotal,
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
            $finalPrice = $discount > 0 ? $price - (($price * $discount) / 100) : $price;

            $products[$productId] = array_merge($cartProduct, [
                'product_id'  => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'price'       => $price,
                'discount'    => $discount,
                'final_price' => round($finalPrice, 2),
                'image'       => $product->main_image,
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
        $categoryIds = ProductService::getCategoryIds($category);

        $products = Product::with(['categories', 'attributeValues'])
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->paginate(15);

        return view('Frontend.shop.Shop', [
            'products'       => $products,
            'category'       => $category,
            'categoriesTree' => ProductService::buildCategoriesTree(),
        ]);
    }

    /** Show a specific product
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug)
    {
        $product = Product::with(['categories.children', 'attributeValues.type', 'variants', 'variantParent'])
            ->where('slug', $slug)
            ->first();

        if (! $product) {
            return view('errors.ProductNotFound');
        }

        $isProductDioptric = ProductService::getProductTree($product);

        return view('Frontend.shop.Show', [
            'product'           => $product,
            'isProductDioptric' => $isProductDioptric,
            'sphValues'         => PrescriptionOptions::SPH,
            'cylValues'         => PrescriptionOptions::CYL,
            'addValues'         => PrescriptionOptions::CYL,
            'axisValues'        => PrescriptionOptions::AXIS,
        ]);
    }


    /** Redirect after succesfull order */
    public function success (){
        return view('Frontend.shop.success');
    }

}
