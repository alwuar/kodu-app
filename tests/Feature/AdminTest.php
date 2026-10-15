<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
        $this->admin = User::factory()->create();
        Storage::fake('public');
    }

    public function test_el_panel_pide_iniciar_sesion(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/productos')->assertRedirect('/admin/login');
    }

    public function test_iniciar_sesion(): void
    {
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'mal'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'password'])->assertRedirect('/admin');
    }

    public function test_paginas_del_panel(): void
    {
        $product = Product::query()->first();

        foreach (['/admin', '/admin/productos', '/admin/productos/create', "/admin/productos/{$product->id}/edit",
            '/admin/categorias', '/admin/categorias/create', '/admin/categorias/martillos-hidraulicos/edit',
            '/admin/pedidos', '/admin/distribuidores', '/admin/mensajes', '/admin/inicio'] as $uri) {
            $this->actingAs($this->admin)->get($uri)->assertOk();
        }
    }

    public function test_crear_categoria_y_aparece_en_el_menu(): void
    {
        $this->actingAs($this->admin)->post('/admin/categorias', [
            'name' => 'Refacciones',
            'singular' => 'Refacción',
            'description' => 'Refacciones originales.',
            'is_active' => '1',
            'show_in_menu' => '1',
        ])->assertRedirect('/admin/categorias');

        $this->assertDatabaseHas(Category::class, ['slug' => 'refacciones']);
        $this->get('/')->assertSee('Refacciones');
        $this->get('/refacciones')->assertOk();
    }

    public function test_no_permite_urls_reservadas(): void
    {
        $this->actingAs($this->admin)->post('/admin/categorias', ['name' => 'Carrito'])->assertSessionHasErrors('slug');
    }

    public function test_crear_producto_con_imagen_pdf_precio_y_ficha(): void
    {
        $category = Category::query()->where('slug', 'accesorios')->first();

        $this->actingAs($this->admin)->post('/admin/productos', [
            'category_id' => $category->id,
            'name' => 'Pinza demoledora',
            'price' => '125,000.50',
            'summary' => 'Pinza para demolición.',
            'machine' => 'excavadora',
            'tons_min' => '20',
            'tons_max' => '30',
            'is_active' => '1',
            'specs' => [
                ['label' => 'Peso', 'value' => '2,100 kg'],
                ['label' => '', 'value' => ''],
            ],
            'image' => UploadedFile::fake()->image('pinza.png', 480, 1200),
            'datasheet' => UploadedFile::fake()->create('ficha.pdf', 300, 'application/pdf'),
        ])->assertRedirect();

        $product = Product::query()->where('slug', 'pinza-demoledora')->first();

        $this->assertSame(12_500_050, $product->price);
        $this->assertSame([['label' => 'Peso', 'value' => '2,100 kg']], $product->specs);
        $this->assertSame(['Excavadora', '20 - 30 ton'], $product->tags());
        Storage::disk('public')->assertExists($product->image);
        Storage::disk('public')->assertExists($product->datasheet);
    }

    public function test_editar_precio(): void
    {
        $product = Product::query()->where('slug', 'kodu-53')->first();

        $this->actingAs($this->admin)->put("/admin/productos/{$product->id}", [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => '199999.99',
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertSame(19_999_999, $product->fresh()->price);
    }

    public function test_no_borra_categoria_con_productos(): void
    {
        $this->actingAs($this->admin)
            ->delete('/admin/categorias/martillos-hidraulicos')
            ->assertSessionHas('error');

        $this->assertDatabaseHas(Category::class, ['slug' => 'martillos-hidraulicos']);
    }

    public function test_cambiar_video_del_inicio(): void
    {
        $this->actingAs($this->admin)->put('/admin/inicio', [
            'video' => UploadedFile::fake()->create('banner.mp4', 2000, 'video/mp4'),
        ])->assertRedirect();

        $path = Setting::get('hero_video');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);

        $this->actingAs($this->admin)->put('/admin/inicio', ['remove_video' => '1'])->assertRedirect();
        $this->assertNull(Setting::get('hero_video'));
        Storage::disk('public')->assertMissing($path);
    }
}
