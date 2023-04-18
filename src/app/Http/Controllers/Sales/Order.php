<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;

class Order extends Controller
{
    public function placeOrder(Request $request)
    {
        $order = new OrderModel([
            'price' => $this->calculatePrice(),
            'customer_id' => Auth::id(),
        ]);

        return view('order.form')
            ->with('order', $order);
    }

    public function saveOrder(Request $request)
    {
        $order = new OrderModel([
            'delivery_address' => $request->input('delivery-address'),
            'price' => $request->input('price'),
            'customer_id' => $request->input('id'),
        ]);

        $order->save();

        $this->saveOrderProduct($order->id);

        return view('order.thanks');
    }

    public function calculatePrice()
    {
        $price = 0.00;
        $items = Session::get('cartItems');
        $different_items = array_count_values($items);
        foreach ($different_items as $id => $quantity) {
            $product = Product::find($id);
            $price += $product['price'] * $quantity;
        }
        return $price;
    }

    public function saveOrderProduct($order_id) {
        $items = Session::get('cartItems');

        foreach ($items as $product_id) {
            $order_product = new OrderProduct([
                'product_id' => $product_id,
                'order_id' => $order_id,
            ]);

            $order_product->save();
        }

        Session::put('cartItems', []);
    }
}
