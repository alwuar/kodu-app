<?php

namespace App\Http\Controllers;

use App\Services\OrderPayments;
use App\Services\Stripe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, Stripe $stripe, OrderPayments $payments): Response
    {
        try {
            $event = $stripe->constructEvent($request->getContent(), $request->header('Stripe-Signature'));
        } catch (RuntimeException $e) {
            Log::warning('Stripe webhook rechazado: '.$e->getMessage());

            return response('Firma inválida', 400);
        }

        $session = $event['data']['object'] ?? [];

        match ($event['type']) {
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded',
            'checkout.session.expired' => $payments->syncFromCheckoutSession($session),
            'checkout.session.async_payment_failed' => $payments->markFailed($session),
            default => null,
        };

        return response('ok');
    }
}
