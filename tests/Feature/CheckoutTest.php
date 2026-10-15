<?php

namespace Tests\Feature;

use App\Mail\OrderPaid;
use App\Models\Order;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);

        config([
            'services.stripe.secret' => 'sk_test_123',
            'services.stripe.webhook_secret' => 'whsec_test',
        ]);
    }

    protected function addToCart(string $slug, int $quantity = 1): Product
    {
        $product = Product::query()->where('slug', $slug)->first();
        $this->post('/carrito', ['product' => $product->id, 'quantity' => $quantity]);

        return $product;
    }

    public function test_crea_pedido_y_manda_a_stripe(): void
    {
        Http::fake([
            'api.stripe.com/v1/checkout/sessions' => Http::response(['id' => 'cs_test_1', 'url' => 'https://checkout.stripe.com/c/pay/cs_test_1']),
        ]);

        $this->addToCart('kodu-53', 2);

        $this->post('/checkout')->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_1');

        $order = Order::query()->first();
        $this->assertSame('pending', $order->status);
        $this->assertSame(37_900_000, $order->total);
        $this->assertSame('cs_test_1', $order->stripe_session_id);
        $this->assertSame(2, $order->items->first()->quantity);

        Http::assertSent(function (HttpRequest $request) use ($order) {
            return $request['mode'] === 'payment'
                && $request['client_reference_id'] === $order->number
                && (int) $request['line_items'][0]['price_data']['unit_amount'] === 18_950_000
                && (int) $request['line_items'][0]['quantity'] === 2
                && $request['line_items'][0]['price_data']['currency'] === 'mxn';
        });
    }

    public function test_sin_llaves_de_stripe_avisa_y_no_crea_pedido(): void
    {
        config(['services.stripe.secret' => null]);
        $this->addToCart('kodu-53');

        $this->post('/checkout')->assertRedirect('/carrito')->assertSessionHas('cart_error');
        $this->assertSame(0, Order::query()->count());
    }

    public function test_webhook_firmado_marca_pagado_y_manda_correos(): void
    {
        Mail::fake();
        $order = $this->pendingOrder();

        $payload = json_encode([
            'id' => 'evt_1',
            'type' => 'checkout.session.completed',
            'data' => ['object' => [
                'id' => 'cs_test_9',
                'client_reference_id' => $order->number,
                'payment_status' => 'paid',
                'status' => 'complete',
                'payment_intent' => 'pi_123',
                'customer_details' => ['name' => 'Ana López', 'email' => 'ana@example.com', 'phone' => '+529991112233'],
                'collected_information' => ['shipping_details' => [
                    'name' => 'Ana López',
                    'address' => ['line1' => 'Calle 60 #100', 'city' => 'Mérida', 'state' => 'YUC', 'postal_code' => '97000', 'country' => 'MX'],
                ]],
            ]],
        ]);

        $this->postStripe($payload)->assertOk();
        // Stripe puede reenviar el evento: no debe duplicar correos
        $this->postStripe($payload)->assertOk();

        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame('ana@example.com', $order->customer_email);
        $this->assertSame('Mérida', $order->shipping_address['city']);
        $this->assertSame('pi_123', $order->stripe_payment_intent);

        Mail::assertSent(OrderPaid::class, 2); // tienda + cliente, una sola vez
    }

    public function test_webhook_con_firma_invalida_se_rechaza(): void
    {
        $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => 't='.time().',v1=falsa',
            'CONTENT_TYPE' => 'application/json',
        ], '{"type":"checkout.session.completed"}')->assertStatus(400);
    }

    public function test_confirmacion_consulta_stripe_si_el_webhook_no_ha_llegado(): void
    {
        Mail::fake();
        $order = $this->pendingOrder();

        Http::fake([
            'api.stripe.com/v1/checkout/sessions/cs_test_9' => Http::response([
                'id' => 'cs_test_9',
                'client_reference_id' => $order->number,
                'payment_status' => 'paid',
                'customer_details' => ['email' => 'ana@example.com', 'name' => 'Ana'],
            ]),
        ]);

        $this->get('/pedido/'.$order->number.'/confirmacion?session_id=cs_test_9')
            ->assertOk()
            ->assertSee('¡Gracias por tu compra!');

        $this->assertTrue($order->fresh()->isPaid());
    }

    public function test_confirmacion_ajena_da_404(): void
    {
        $order = $this->pendingOrder();

        $this->get('/pedido/'.$order->number.'/confirmacion')->assertNotFound();
        $this->get('/pedido/'.$order->number.'/confirmacion?session_id=otra')->assertNotFound();
    }

    protected function pendingOrder(): Order
    {
        $product = Product::query()->where('slug', 'kodu-53')->first();

        $order = Order::query()->create([
            'status' => 'pending',
            'subtotal' => $product->price,
            'total' => $product->price,
            'stripe_session_id' => 'cs_test_9',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 1,
            'total' => $product->price,
        ]);

        return $order;
    }

    protected function postStripe(string $payload)
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test');

        return $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
            'CONTENT_TYPE' => 'application/json',
        ], $payload);
    }
}
