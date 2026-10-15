<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('estado');

        return view('admin.orders.index', [
            'orders' => Order::query()
                ->withCount('items')
                ->when($status, fn ($q) => $q->where('status', $status), fn ($q) => $q->where('status', '!=', 'pending'))
                ->latest()
                ->paginate(25)
                ->withQueryString(),
            'status' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items.product.category')]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update($request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]));

        return back()->with('status', 'Pedido actualizado.');
    }
}
