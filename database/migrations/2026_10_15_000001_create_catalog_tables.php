<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('singular')->nullable();
            $table->text('description')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('kicker')->nullable();
            $table->text('summary')->nullable();
            $table->unsignedBigInteger('price')->default(0); // centavos MXN, IVA incluido
            $table->string('image')->nullable();
            $table->string('datasheet')->nullable();
            $table->string('machine')->nullable();
            $table->unsignedSmallInteger('tons_min')->nullable();
            $table->unsignedSmallInteger('tons_max')->nullable();
            $table->string('box')->nullable();
            $table->json('specs')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();

            $table->unique(['category_id', 'slug']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
