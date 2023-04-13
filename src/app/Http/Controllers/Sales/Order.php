<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;

class Order extends Controller
{
    public function placeOrder(Request $request)
    {
        $order = new OrderModel();
        $order->setPrice($this->calculatePrice());
        $order->setCustomerId(Auth::id());
        return view('order.form')
            ->with('order', $order);
    }

    public function saveOrder(Request $request)
    {
        $order = new OrderModel();
        $order->setDeliveryAddress($request->input('delivery-address'));
        $order->setPrice($request->input('price'));
        $order->setCustomerId($request->input('id'));

        $product_data['order_id'] = $order->save();

        $items = Session::get('cartItems');

        foreach ($items as $id) {
            $product_data['product_id'] = $id;
            $product_data['created_at'] = $product_data['updated_at'] = date('Y-m-d', time());
            DB::table('order_products')->insert($product_data);
        }

        Session::put('cartItems', []);

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
}
