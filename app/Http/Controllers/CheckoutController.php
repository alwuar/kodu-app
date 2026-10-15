<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderPayments;
use App\Services\Stripe;
use App\Support\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CheckoutController extends Controller
{
    /** Límite de Stripe por cobro en MXN: 999,999.99 */
    protected const STRIPE_MAX_AMOUNT = 99_999_999;

    /** Mínimo de Stripe en MXN: $10.00 */
    protected const STRIPE_MIN_AMOUNT = 1_000;

    public function store(Cart $cart, Stripe $stripe): Response
    {
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return redirect()->route('cart');
        }

        if ($lines->contains(fn ($line) => $line['unit_price'] <= 0)) {
            return $this->back('Uno de los productos no tiene precio todavía. Contáctanos para cotizarlo.');
        }

        $subtotal = $cart->subtotal($lines);

        if ($subtotal < self::STRIPE_MIN_AMOUNT || $subtotal > self::STRIPE_MAX_AMOUNT) {
            return $this->back('El total supera el límite para pago con tarjeta. Contáctanos y te ayudamos a completar tu compra.');
        }

        if (! $stripe->isConfigured()) {
            return $this->back('El pago en línea no está disponible por el momento. Contáctanos para completar tu compra.');
        }

        $order = DB::transaction(function () use ($lines, $subtotal) {
            $order = Order::query()->create([
                'status' => 'pending',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'currency' => config('services.stripe.currency', 'mxn'),
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'name' => $line['product']->name,
                    'sku' => $line['product']->sku,
                    'unit_price' => $line['unit_price'],
                    'quantity' => $line['quantity'],
                    'total' => $line['total'],
                ]);
            }

            return $order;
        });

        try {
            $session = $stripe->createCheckoutSession(
                $order->load('items'),
                successUrl: route('checkout.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
                cancelUrl: route('cart'),
            );
        } catch (Throwable $e) {
            Log::error('Stripe: no se pudo crear la sesión de pago', ['order' => $order->number, 'error' => $e->getMessage()]);
            $order->update(['status' => 'failed']);

            return $this->back('No pudimos conectar con el procesador de pagos. Intenta de nuevo en unos minutos.');
        }

        $order->update(['stripe_session_id' => $session['id']]);
        session()->push('orders', $order->number);

        // Redirección a la página de pago de Stripe (fuera del sitio)
        return redirect()->away($session['url'], 303);
    }

    public function success(Request $request, Order $order, Stripe $stripe, OrderPayments $payments, Cart $cart): View
    {
        $sessionId = (string) $request->query('session_id');
        $ownsOrder = in_array($order->number, session('orders', []), true)
            || ($sessionId !== '' && hash_equals((string) $order->stripe_session_id, $sessionId));

        abort_unless($ownsOrder, 404);

        // Si el webhook todavía no llega, se consulta la sesión directamente
        if (! $order->isPaid() && $order->stripe_session_id && $stripe->isConfigured()) {
            try {
                $order = $payments->syncFromCheckoutSession($stripe->retrieveCheckoutSession($order->stripe_session_id)) ?? $order;
            } catch (Throwable $e) {
                Log::warning('Stripe: no se pudo consultar la sesión', ['order' => $order->number, 'error' => $e->getMessage()]);
            }
        }

        if ($order->isPaid()) {
            $cart->clear();
        }

        return view('pages.checkout-success', ['order' => $order->load('items')]);
    }

    protected function back(string $message): RedirectResponse
    {
        return redirect()->route('cart')->with('cart_error', $message);
    }
}
