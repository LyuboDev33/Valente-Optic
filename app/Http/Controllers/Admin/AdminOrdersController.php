<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AdminOrdersController extends Controller
{
    /** Show all orders for the admin*/
    public function index () {

        $orders = Order::orderBy('id', 'desc')
                  ->get();


        return view('admin.Orders.Index', [
            'orders' => $orders
        ]);
    }

    /**
     * @param string $order_number
     * @return View
     */
    public function show ($order_number) {
        $order = Order::with('products')
                ->where('order_number', $order_number)
                ->first();

        return view('admin.Orders.Show', [
            'order' => $order
        ]);
    }



}
