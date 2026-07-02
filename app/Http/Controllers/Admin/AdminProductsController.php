<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProductRequest;
use App\Models\Admin\Glass;
use App\Models\Admin\LensIndex;
use App\Models\Admin\LensIndexProduct;
use App\Models\Admin\ProductVariants;
use App\Models\AttributeType;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminProductsController extends Controller
{
    /** Return all products */
    public function index()
    {
        $products = Product::with(['categories', 'attributeValues'])
            ->latest()
            ->paginate(15);

        return view('admin.Products.Index', compact('products'));
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

    /** Create the product view */
    public function createProductView()
    {
        $categories = Category::with('children')
            ->whereNull('category_parent_id')
            ->get();

        $categories = $this->flattenCategoryTree($categories);

        $attributeTypes = AttributeType::with('values')
            ->orderBy('name')
            ->get();

        return view('admin.Products.CreateProductView', [
            'categories'     => $categories,
            'attributeTypes' => $attributeTypes,
        ]);
    }

    /** Show the product
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug)
    {
        $product = Product::with(['categories', 'attributeValues', 'variants', 'variantParent'])
            ->where('slug', $slug)
            ->firstOrFail();


        $categories = Category::with('children')
            ->whereNull('category_parent_id')
            ->get();

        $categories = $this->flattenCategoryTree($categories);

        $attributeTypes = AttributeType::with('values')
            ->orderBy('name')
            ->get();

        $selectedAttributeValueIds = $product->attributeValues->pluck('id')->toArray();

        $glasses = Glass::with('values')->orderBy('id', 'desc')->get();
        $lens = LensIndex::orderBy('id', 'desc')->get();

        return view('admin.Products.Show', [
            'product'                   => $product,
            'categories'                => $categories,
            'attributeTypes'            => $attributeTypes,
            'selectedAttributeValueIds' => $selectedAttributeValueIds,
            'glasses'                   => $glasses,
            'lens'                      => $lens
        ]);
    }

    /** Create the product and a variation if $product is not null
     *
     * @param CreateProductRequest $request
     * @param Product|null $product
     * @return RedirectResponse
     */
    public function create(CreateProductRequest $request, ?Product $product = null): RedirectResponse
    {
        /** Validate the request */
        $validated = $request->validated();

        $parentProduct = $product;

        $slug = Str::slug($validated['name']) . '-' . Str::slug($validated['sku']);

        if (Product::where('slug', $slug)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'Вече съществува продукт със същото име/SKU.',
                ]);
        }

        $file = $request->file('main_image');
        $mainImageName = str_replace(' ', '', time() . '_' . $file->getClientOriginalName());
        $file->move(public_path('/assets/images/products'), $mainImageName);

        $galleryNames = [];

        foreach ($request->file('gallery') as $galleryFile) {
            $galleryName = str_replace(' ', '', time() . '_' . $galleryFile->getClientOriginalName());
            $galleryFile->move(public_path('/assets/images/product_gallery'), $galleryName);
            $galleryNames[] = $galleryName;
        }

        $createdProduct = Product::create([
            'name'        => $validated['name'],
            'sku'         => $validated['sku'],
            'slug'        => $slug,
            'discount'    => $validated['discount'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'main_image'  => $mainImageName,
            'category_id' => $validated['category_id'],
            'gallery'     => $galleryNames,
        ]);

        $category = Category::with('parent')
            ->findOrFail($validated['category_id']);

        $categoriesToInsert = [];

        while ($category) {
            $categoriesToInsert[] = $category->id;
            $category = $category->parent;
        }

        foreach ($categoriesToInsert as $categoryId) {
            ProductCategory::create([
                'product_id'  => $createdProduct->id,
                'category_id' => $categoryId,
            ]);
        }

        $valueIds = array_values(array_filter($request->input('attribute_values', [])));

        if (! empty($valueIds)) {
            $createdProduct->attributeValues()->attach($valueIds);
        }

        if ($parentProduct) {
            ProductVariants::create([
                'parent_product_id'  => $parentProduct->id,
                'variant_product_id' => $createdProduct->id,
            ]);

            return back()->with('success', 'Вариантът беше добавен успешно!');
        }

        return back()->with('success', 'Продуктът беше добавен успешно!');
    }


    /** Update the product
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Request $request, Product $product): RedirectResponse
    {

        $request->merge([
            'attribute_values' => array_values(array_filter($request->input('attribute_values', [])))
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product->id)],
            'category_id'        => ['required', 'exists:categories,id'],
            'description'        => ['required', 'string'],
            'discount'           => ['nullable', 'numeric', 'min:0', 'max:99'],
            'stock'              => ['required', 'integer', 'min:0'],
            'price'              => ['required', 'numeric', 'min:0'],
            'main_image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'gallery'            => ['nullable', 'array'],
            'gallery.*'          => ['image', 'mimes:jpg,jpeg,png,webp'],

            'attribute_values'   => ['nullable', 'array'],
            // 'attribute_values.*' => ['exists:attribute_values,id'],
        ], [
            'name.required' => 'Името на продукта е задължително.',
            // 'name.unique'   => 'Вече съществува продукт със същото име.',

            'sku.required' => 'SKU е задължително.',
            'sku.unique'   => 'Вече съществува продукт със същото SKU.',

            'discount.max'  => 'Максималната отстъпка може да бъде 99%',
            'discount.min'  => 'Минималната отстъпка трябва да бъде 0%',

            'category_id.required' => 'Моля изберете категория.',
            'category_id.exists'   => 'Избраната категория не съществува.',

            'description.required' => 'Описанието на продукта е задължително.',

            'price.required' => 'Цената е задължителна.',
            'price.numeric'  => 'Цената трябва да бъде число.',
            'price.min'      => 'Цената не може да бъде отрицателна.',

            'main_image.image' => 'Файлът трябва да бъде изображение.',
            'main_image.mimes' => 'Главната снимка трябва да бъде JPG, JPEG, PNG или WEBP.',
            'main_image.max'   => 'Главната снимка не може да бъде по-голяма от 0.5MB.',

            'gallery.array'   => 'Галерията е невалидна.',
            'gallery.*.image' => 'Всеки файл в галерията трябва да бъде изображение.',
            'gallery.*.mimes' => 'Снимките трябва да бъдат JPG, JPEG, PNG или WEBP.',
            'gallery.*.max'   => 'Всяка снимка не може да бъде по-голяма от 0.5MB.',

            // 'attribute_values.*.integer' => 'Невалиден атрибут.',
            'attribute_values.*.exists'   => 'Избран атрибут не съществува.',
        ]);

        $slug = Str::slug($validated['name']) . '-' . Str::slug($validated['sku']);

        $mainImageName = $product->main_image;

        if ($request->hasFile('main_image')) {
            if ($product->main_image) {
                $oldPath = public_path('/assets/images/products/' . $product->main_image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('main_image');
            $mainImageName = str_replace(' ', '', time() . '_' . $file->getClientOriginalName());
            $file->move(public_path('/assets/images/products'), $mainImageName);
        }

        // === GALLERY ===
        $galleryNames = $product->gallery ?? [];

        if ($request->hasFile('gallery')) {
            foreach ($galleryNames as $oldGallery) {
                $oldPath = public_path('/assets/images/product_gallery/' . $oldGallery);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $galleryNames = [];

            foreach ($request->file('gallery') as $galleryFile) {
                $galleryName = str_replace(' ', '', time() . '_' . $galleryFile->getClientOriginalName());
                $galleryFile->move(public_path('/assets/images/product_gallery'), $galleryName);
                $galleryNames[] = $galleryName;
            }
        }

        $product->update([
            'name'        => $validated['name'],
            'sku'         => $validated['sku'],
            'slug'        => $slug,
            'discount'    => $validated['discount'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'main_image'  => $mainImageName,
            'category_id' => $validated['category_id'],
            'gallery'     => $galleryNames,
        ]);

        $categoryIds = [];
        $category = Category::with('parent')->findOrFail($validated['category_id']);

        while ($category) {
            $categoryIds[] = $category->id;
            $category = $category->parent;
        }

        $product->categories()->sync($categoryIds);

        $valueIds = array_values(array_filter($request->input('attribute_values', [])));
        $product->attributeValues()->sync($valueIds);

        return  redirect(route('admin.products.show', $product->slug))
            ->with('success', 'Продуктът беше обновен успешно!');
    }


    /** Delete a product
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Продуктът беше изтрит.');
    }
}
