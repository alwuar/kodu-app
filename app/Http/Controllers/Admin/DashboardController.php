<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\DealerRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $paidThisMonth = Order::query()->whereNotNull('paid_at')->where('paid_at', '>=', now()->startOfMonth());

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Ventas del mes', 'value' => \App\Support\Money::format((int) (clone $paidThisMonth)->sum('total')), 'url' => route('admin.orders.index', ['estado' => 'paid'])],
                ['label' => 'Pedidos pagados del mes', 'value' => (clone $paidThisMonth)->count(), 'url' => route('admin.orders.index', ['estado' => 'paid'])],
                ['label' => 'Solicitudes de distribuidor nuevas', 'value' => DealerRequest::query()->where('status', 'new')->count(), 'url' => route('admin.dealers.index')],
                ['label' => 'Mensajes sin leer', 'value' => ContactMessage::query()->where('is_read', false)->count(), 'url' => route('admin.messages.index')],
                ['label' => 'Productos activos', 'value' => Product::query()->where('is_active', true)->count(), 'url' => route('admin.products.index')],
            ],
            'orders' => Order::query()->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->latest('paid_at')->limit(8)->get(),
            'dealers' => DealerRequest::query()->latest()->limit(5)->get(),
        ]);
    }
}
