<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected Cart $cart)
    {
    }

    public function index(): View
    {
        $lines = $this->cart->lines();

        return view('pages.cart', [
            'lines' => $lines,
            'subtotal' => $this->cart->subtotal($lines),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product' => ['required', 'integer'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:'.Cart::MAX_QUANTITY],
        ]);

        $product = Product::query()->whereKey($data['product'])->where('is_active', true)->firstOrFail();

        $this->cart->add($product, (int) ($data['quantity'] ?? 1));

        return redirect()->route('cart')->with('cart_status', $product->name.' se agregó a tu carrito.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:'.Cart::MAX_QUANTITY],
            'step' => ['nullable', 'integer', 'in:-1,1'],
        ]);

        $this->cart->update($product->id, (int) $data['quantity'] + (int) ($data['step'] ?? 0));

        return redirect()->route('cart');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->cart->remove($product->id);

        return redirect()->route('cart')->with('cart_status', $product->name.' se quitó del carrito.');
    }
}
