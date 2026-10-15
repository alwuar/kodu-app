<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('status')->default('pending')->index(); // pending, paid, processing, shipped, cancelled, failed
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->json('shipping_address')->nullable();
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('total');
            $table->string('currency', 3)->default('mxn');
            $table->string('stripe_session_id')->nullable()->unique();
            $table->string('stripe_payment_intent')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('unit_price');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('total');
            $table->timestamps();
        });

        Schema::create('dealer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('city');
            $table->string('phone');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('source')->nullable();
            $table->string('status')->default('new')->index(); // new, contacted, approved, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('dealer_requests');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
