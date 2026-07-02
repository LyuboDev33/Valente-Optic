<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Services\ProductService;


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


        $isProductDioptric = ProductService::isProductDioptric($product);

        /** Check if the products is dioptric */
        if ($isProductDioptric) {

            /** If the product is dioptric, it requires  */
            if (! $hasUploadedPrescription && ! $hasManualPrescription) {
                return back()->withErrors([
                    'prescription' => 'Моля, качете снимка с рецепта или въведете ръчно данните за диоптър.',
                ]);
            }
        }


        $prescriptionImageName = null;

        if ($hasUploadedPrescription) {
            $file = $request->file('prescription_image');
            $prescriptionImageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/images/prescriptions'), $prescriptionImageName);
        }

        $finalPrice = $product->discount
            ? $product->price - (($product->price * $product->discount) / 100)
            : $product->price;

        if (isset($products[$key])) {
            $products[$key]['quantity'] += $quantity;

            if ($prescriptionImageName) {
                $products[$key]['prescription_image'] = $prescriptionImageName;
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

                'prescription_image' => $prescriptionImageName,
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

    /**
     *
     * @param Request
     * @return RedirectResponse
     */
    public function create(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'delivery_method' => ['required'],

            'city' => ['nullable', 'string', 'max:255'],
            'billing_address' => ['nullable', 'string', 'max:255'],
            'office_list' => ['nullable', 'string', 'max:255'],

            'request_invoice' => ['nullable', 'boolean'],

            'company_name' => ['nullable', 'string', 'max:255'],
            'company_mol' => ['nullable', 'string', 'max:255'],
            'company_bulstat' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
        ]);

        $personalDelivery =
            !empty($validated['city']) &&
            !empty($validated['billing_address']);

        $officeDelivery =
            !empty($validated['office_list']);

        if (! $personalDelivery && ! $officeDelivery) {

            return back()
                ->withErrors([
                    'delivery' => 'Моля попълнете адрес за доставка или изберете офис на Speedy и попълнете нужните данни.'
                ])
                ->withInput();
        }

        // dd($request);

        $cartProducts = Session::get('products', []);

        if (empty($cartProducts)) {
            return back()->withErrors([
                'cart' => 'Количката е празна.'
            ]);
        }

        DB::transaction(function () use ($validated, $cartProducts) {

            $subtotal = 0;

            foreach ($cartProducts as $product) {
                $subtotal += $product['final_price'] * $product['quantity'];
            }

            $order = Order::create([
                'order_number' => 'ORD-' . date('ymd') . '-' . random_int(1000, 9999),
                'first_name' => $validated['fname'],
                'last_name' => $validated['lname'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],

                'delivery_method' => $validated['delivery_method'],

                'city' => $validated['city'] ?? null,
                'personal_address' => $validated['billing_address'] ?? null,

                'courier' => !empty($validated['office_list']) ? 'speedy' : null,
                'office_list' => $validated['office_list'] ?? null,

                'request_invoice' => $validated['request_invoice'] ?? false,

                'company_name' => $validated['company_name'] ?? null,
                'company_mol' => $validated['company_mol'] ?? null,
                'company_bulstat' => $validated['company_bulstat'] ?? null,
                'company_address' => $validated['company_address'] ?? null,

                'subtotal' => $subtotal,
                'delivery_price' => 0,
                'total' => $subtotal,

                'payment_option' => 'cash_on_delivery',
                'status' => Order::STATUS_PENDING,
            ]);

            foreach ($cartProducts as $product) {
                $databaseProduct = Product::where('id', $product['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $databaseProduct) {
                    throw new \Exception('Продуктът не съществува.');
                }

                $currentStock = (int) $databaseProduct->stock;
                $orderedQuantity = (int) $product['quantity'];

                if ($currentStock < $orderedQuantity) {
                    throw new \Exception('Няма достатъчна наличност за продукт: ' . $product['name']);
                }

                $databaseProduct->update([
                    'stock' => $currentStock - $orderedQuantity,
                ]);

                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],

                    'product_name' => $product['name'],
                    'product_slug' => $product['slug'],
                    'product_image' => $product['image'],

                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'final_price' => $product['final_price'],
                    'quantity' => $product['quantity'],

                    'prescription_image' => $product['prescription_image'] ?? null,

                    'right_eye' => $product['right_eye'] ?? null,
                    'left_eye' => $product['left_eye'] ?? null,
                ]);
            }

            Mail::to($order->email)->send(new OrderCreated($order));
        });



        Session::forget('products');



        return redirect(route('checkout.succes'));
    }
}
