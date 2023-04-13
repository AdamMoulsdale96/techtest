<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;

class Order extends Controller
{
    public function placeOrder(Request $request)
    {
        $order = new \App\Models\Order();
        $order->price = $this->getPrice();
//        $order->customer_id = Auth::id();
        return view('order.form')
            ->with('order', $order);
    }

    public function saveOrder(Request $request)
    {
        $name = $request->input('name');
        $data['delivery_address'] = $request->input('delivery-address');
        $data['price'] = $request->input('price');
        $data['customer_id'] = $request->input('id');
        $data['created_at'] = $data['updated_at'] = date('Y-m-d', time());

        $product_data['order_id'] = DB::table('orders')->insert($data);

        $items = Session::get('cartItems');

        foreach ($items as $id) {
            $product_data['product_id'] = $id;
            $product_data['created_at'] = $product_data['updated_at'] = date('Y-m-d', time());
            DB::table('order_products')->insert($product_data);
        }

        Session::put('cartItems', []);

        return view('order.thanks');
    }

    public function getPrice() {
        $price = 0.00;
        $items = Session::get('cartItems');
        $different_items = array_count_values($items);
        foreach ($different_items as $id => $quantity) {
            $product = Product::find($id);
            $price += $product['price'] * $quantity;
        }
        return $price;
    }
}
