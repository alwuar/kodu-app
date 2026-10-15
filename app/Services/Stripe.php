<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Cliente mínimo de la API de Stripe (Checkout + webhooks) sin dependencias extra.
 */
class Stripe
{
    protected const API = 'https://api.stripe.com/v1/';

    public function isConfigured(): bool
    {
        return filled(config('services.stripe.secret'));
    }

    /**
     * Crea la sesión de Stripe Checkout para un pedido y regresa su respuesta
     * (contiene `id` y `url` a donde se manda al cliente).
     */
    public function createCheckoutSession(Order $order, string $successUrl, string $cancelUrl): array
    {
        $currency = config('services.stripe.currency', 'mxn');

        $lineItems = $order->items->values()->map(fn ($item) => [
            'quantity' => $item->quantity,
            'price_data' => [
                'currency' => $currency,
                'unit_amount' => $item->unit_price,
                'product_data' => array_filter([
                    'name' => $item->name,
                    'metadata' => ['product_id' => (string) $item->product_id, 'sku' => (string) $item->sku],
                ]),
            ],
        ])->all();

        $payload = [
            'mode' => 'payment',
            'locale' => 'es-419',
            'client_reference_id' => $order->number,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'line_items' => $lineItems,
            'phone_number_collection' => ['enabled' => 'true'],
            'billing_address_collection' => 'required',
            'shipping_address_collection' => ['allowed_countries' => config('services.stripe.shipping_countries', ['MX'])],
            'metadata' => ['order_id' => (string) $order->id, 'order_number' => $order->number],
            'payment_intent_data' => [
                'description' => 'Pedido '.$order->number,
                'metadata' => ['order_id' => (string) $order->id, 'order_number' => $order->number],
            ],
        ];

        return $this->request()->post('checkout/sessions', $payload)->throw()->json();
    }

    public function retrieveCheckoutSession(string $id): array
    {
        return $this->request()->get('checkout/sessions/'.$id)->throw()->json();
    }

    /**
     * Valida la firma del webhook (encabezado Stripe-Signature) y regresa el evento.
     *
     * @throws RuntimeException
     */
    public function constructEvent(string $payload, ?string $signatureHeader, int $tolerance = 300): array
    {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret) {
            throw new RuntimeException('Falta STRIPE_WEBHOOK_SECRET.');
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', (string) $signatureHeader) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, null);

            if ($key === 't') {
                $timestamp = (int) $value;
            } elseif ($key === 'v1') {
                $signatures[] = $value;
            }
        }

        if (! $timestamp || ! $signatures) {
            throw new RuntimeException('Encabezado de firma inválido.');
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
        $valid = collect($signatures)->contains(fn ($sig) => hash_equals($expected, (string) $sig));

        if (! $valid) {
            throw new RuntimeException('La firma no coincide.');
        }

        if (abs(time() - $timestamp) > $tolerance) {
            throw new RuntimeException('La firma expiró.');
        }

        $event = json_decode($payload, true);

        if (! is_array($event) || ! isset($event['type'])) {
            throw new RuntimeException('Evento inválido.');
        }

        return $event;
    }

    protected function request(): PendingRequest
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Stripe no está configurado (STRIPE_SECRET).');
        }

        return Http::baseUrl(self::API)
            ->withToken(config('services.stripe.secret'))
            ->asForm()
            ->acceptJson()
            ->timeout(20)
            ->retry(2, 300, throw: false);
    }
}
