<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrdersController extends Controller
{


    /** Add a product to the session
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function addProduct(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],

            'prescription_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],

            'right_eye' => ['nullable', 'array'],
            'right_eye.sph' => ['nullable', 'string'],
            'right_eye.cyl' => ['nullable', 'string'],
            'right_eye.axis' => ['nullable', 'string'],
            'right_eye.add' => ['nullable', 'string'],

            'left_eye' => ['nullable', 'array'],
            'left_eye.sph' => ['nullable', 'string'],
            'left_eye.cyl' => ['nullable', 'string'],
            'left_eye.axis' => ['nullable', 'string'],
            'left_eye.add' => ['nullable', 'string'],
        ]);

        $quantity = (int) $validated['quantity'];
        $stock = (int) $product->stock;

        if ($stock <= 0) {
            return back()->withErrors([
                'stock' => 'Този продукт няма наличност.',
            ]);
        }

        $products = Session::get('products', []);

        $key = $product->id;

        $existingQuantity = isset($products[$key]) ? (int) $products[$key]['quantity'] : 0;

        if (($existingQuantity + $quantity) > $stock) {
            return back()->withErrors([
                'quantity' => 'Няма достатъчна наличност за желаното количество.',
            ]);
        }

        $hasUploadedPrescription = $request->hasFile('prescription_image');

        $rightEye = $validated['right_eye'] ?? [];
        $leftEye = $validated['left_eye'] ?? [];

        $hasManualPrescription =
            !empty(array_filter($rightEye)) ||
            !empty(array_filter($leftEye));

        if (! $hasUploadedPrescription && ! $hasManualPrescription) {
            return back()->withErrors([
                'prescription' => 'Моля, качете снимка с рецепта или въведете ръчно данните за диоптър.',
            ]);
        }

        $prescriptionImagePath = null;

        if ($hasUploadedPrescription) {
            $prescriptionImagePath = $request
                ->file('prescription_image')
                ->store('prescriptions', 'public');
        }

        $finalPrice = $product->discount
            ? $product->price - (($product->price * $product->discount) / 100)
            : $product->price;

        if (isset($products[$key])) {
            $products[$key]['quantity'] += $quantity;

            if ($prescriptionImagePath) {
                $products[$key]['prescription_image'] = $prescriptionImagePath;
            }

            if ($hasManualPrescription) {
                $products[$key]['right_eye'] = $rightEye;
                $products[$key]['left_eye'] = $leftEye;
            }
        } else {
            $products[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'discount' => $product->discount,
                'final_price' => (float) $finalPrice,
                'quantity' => $quantity,
                'image' => $product->main_image,

                'prescription_image' => $prescriptionImagePath,
                'right_eye' => $rightEye,
                'left_eye' => $leftEye,
            ];
        }

        Session::put('products', $products);

        return back()->with('success', 'Продуктът беше добавен успешно в количката!');
    }


    /** Remove a product from the cart
     *
     * @param int $productId
     * @return RedirectResponse
     */
    public function removeProduct(int $productId): RedirectResponse
    {
        $products = Session::get('products', []);

        if (isset($products[$productId])) {
            /*
         * Optional:
         * Delete uploaded prescription image as well
         */
            if (! empty($products[$productId]['prescription_image'])) {

                Storage::disk('public')->delete(
                    $products[$productId]['prescription_image']
                );
            }

            unset($products[$productId]);

            Session::put('products', $products);
        }

        return back()->with(
            'success',
            'Продуктът беше премахнат успешно от количката.'
        );
    }
}
