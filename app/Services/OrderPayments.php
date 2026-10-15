<?php

namespace App\Services;

use App\Mail\OrderPaid;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Aplica a un pedido lo que reporta Stripe sobre su sesión de Checkout.
 * Se usa desde el webhook y desde la página de confirmación (lo que llegue primero).
 */
class OrderPayments
{
    public function syncFromCheckoutSession(array $session): ?Order
    {
        $order = Order::query()
            ->where('stripe_session_id', $session['id'] ?? '')
            ->orWhere('number', $session['client_reference_id'] ?? '')
            ->first();

        if (! $order) {
            return null;
        }

        $details = $session['customer_details'] ?? [];
        $shipping = $session['collected_information']['shipping_details']
            ?? $session['shipping_details']
            ?? null;

        $order->fill(array_filter([
            'customer_name' => $shipping['name'] ?? $details['name'] ?? null,
            'customer_email' => $details['email'] ?? null,
            'customer_phone' => $details['phone'] ?? null,
            'shipping_address' => $shipping
                ? ['name' => $shipping['name'] ?? null] + ($shipping['address'] ?? [])
                : ($details['address'] ?? null),
            'stripe_payment_intent' => is_string($session['payment_intent'] ?? null) ? $session['payment_intent'] : null,
        ]));

        if (($session['status'] ?? null) === 'expired' && $order->status === 'pending') {
            $order->status = 'cancelled';
        }

        $order->save();

        if (($session['payment_status'] ?? null) === 'paid') {
            // Actualización atómica: si el webhook y la página de confirmación llegan
            // al mismo tiempo, solo uno marca el pago y manda los correos.
            $justPaid = Order::query()
                ->whereKey($order->id)
                ->whereNull('paid_at')
                ->update(['status' => 'paid', 'paid_at' => now()]) === 1;

            $order->refresh();

            if ($justPaid) {
                $this->notifyPaid($order);
            }
        }

        return $order;
    }

    public function markFailed(array $session): void
    {
        Order::query()
            ->where('stripe_session_id', $session['id'] ?? '')
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    protected function notifyPaid(Order $order): void
    {
        $order->load('items');

        try {
            Mail::to(config('kodu.notify_email'))->send(new OrderPaid($order, forStore: true));

            if ($order->customer_email) {
                Mail::to($order->customer_email)->send(new OrderPaid($order));
            }
        } catch (Throwable $e) {
            Log::error('No se pudo enviar el correo del pedido '.$order->number, ['error' => $e->getMessage()]);
        }
    }
}
