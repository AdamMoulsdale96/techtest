<?php

namespace App\Http\Controllers\Catalogue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\Product as ProductModel;

class Product extends Controller
{
    public function index()
    {
        return view('catalogue.index')
        ->with('products', ProductModel::all());
    }

    public function addToCart(string $id)
    {
        if (!$id) {
            return null;
        }
        $cart = Session::get('cartItems') ?? [];
        if (is_string($cart)) {
            $cart = [$cart];
        }


        if (ProductModel::find($id)) {
            $cart[] = $id;
        }
        Session::put('cartItems', $cart);
        return back();
    }

    public function removeFromCart($id)
    {
        $items = Session::get('cartItems');
        foreach ($items as $item_id => $item) {
            if ($item === $id) {
                unset($items[$item_id]);
            }
        }
        Session::put('cartItems', $items);
        return back();
    }

    public function viewCart()
    {
        $product_list = [];
        $items = Session::get('cartItems');
        $different_items = array_count_values($items);
        foreach ($different_items as $id => $quantity) {
            $product = ProductModel::find($id);
            $product_list[$id] = [
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
        return view('order.cart')
        ->with('products', $product_list);
    }


}
