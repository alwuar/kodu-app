<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product' => ['required', 'string', 'in:'.implode(',', array_keys(config('catalog.products')))],
        ]);

        $product = config('catalog.products.'.$data['product']);

        $cart = $request->session()->get('cart', []);
        $cart[$data['product']] = ($cart[$data['product']] ?? 0) + 1;
        $request->session()->put('cart', $cart);

        $message = $product['name'].' se agregó a tu carrito.';

        if ($product['price']) {
            $message .= ' Precio: '.$product['price'].'.';
        }

        return back()->with('cart_status', $message);
    }
}
