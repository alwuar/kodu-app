<?php

namespace Tests\Feature;

use App\Mail\ContactReceived;
use App\Mail\DealerRequestReceived;
use App\Models\ContactMessage;
use App\Models\DealerRequest;
use App\Models\Product;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CatalogSeeder::class);
    }

    public function test_paginas_principales_responden(): void
    {
        foreach (['/', '/martillos-hidraulicos', '/martillos-hidraulicos/kodu-53', '/accesorios', '/contacto', '/distribuidores', '/carrito', '/sitemap.xml', '/robots.txt'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_seo_basico_en_ficha_de_producto(): void
    {
        $this->get('/martillos-hidraulicos/kodu-53')
            ->assertSee('<title>KODU 53 — Martillo hidráulico | KODU</title>', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('Descargar ficha técnica (PDF)');
    }

    public function test_el_precio_no_se_muestra_en_el_catalogo(): void
    {
        $this->get('/martillos-hidraulicos')->assertDontSee('189,500');
        $this->get('/martillos-hidraulicos/kodu-53')->assertDontSee('189,500');
    }

    public function test_producto_de_otra_categoria_u_oculto_da_404(): void
    {
        $this->get('/accesorios/kodu-53')->assertNotFound();
        $this->get('/martillos-hidraulicos/no-existe')->assertNotFound();
        $this->get('/no-existe')->assertNotFound();

        Product::query()->where('slug', 'kodu-68')->update(['is_active' => false]);
        $this->get('/martillos-hidraulicos/kodu-68')->assertNotFound();
    }

    public function test_carrito_agrega_actualiza_y_quita(): void
    {
        $product = Product::query()->where('slug', 'kodu-53')->first();

        $this->post('/carrito', ['product' => $product->id])->assertRedirect('/carrito');
        $this->assertSame([$product->id => 1], session('cart'));

        $this->get('/carrito')->assertSee('$189,500.00 MXN');

        $this->patch('/carrito/'.$product->id, ['quantity' => 1, 'step' => 1])->assertRedirect('/carrito');
        $this->assertSame([$product->id => 2], session('cart'));

        $this->patch('/carrito/'.$product->id, ['quantity' => 5])->assertRedirect('/carrito');
        $this->get('/carrito')->assertSee('$947,500.00 MXN');

        $this->delete('/carrito/'.$product->id)->assertRedirect('/carrito');
        $this->assertSame([], session('cart'));
    }

    public function test_contacto_valida_y_guarda(): void
    {
        Mail::fake();

        $this->post('/contacto', [])->assertSessionHasErrors(['nombre', 'apellido', 'email', 'telefono']);

        $this->post('/contacto', [
            'nombre' => 'Cecilia',
            'apellido' => 'Acevedo',
            'email' => 'cecy@example.com',
            'pais' => 'MX',
            'telefono' => '9991234567',
            'empresa' => 'Constructora',
        ])->assertRedirect('/contacto')->assertSessionHas('status');

        $this->assertDatabaseHas(ContactMessage::class, ['email' => 'cecy@example.com', 'phone' => '+52 9991234567']);
        Mail::assertSent(ContactReceived::class);
    }

    public function test_solicitud_de_distribuidor(): void
    {
        Mail::fake();

        $this->from('/')->post('/distribuidores', ['origen' => 'modal'])
            ->assertRedirect('/')
            ->assertSessionHasErrorsIn('dealer', ['nombre', 'apellido', 'ciudad', 'telefono', 'email']);

        $this->from('/')->post('/distribuidores', [
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'ciudad' => 'Cancún',
            'telefono' => '9981234567',
            'email' => 'juan@example.com',
            'empresa' => 'Maquinaria JP',
            'origen' => 'modal',
        ])->assertRedirect('/')->assertSessionHas('dealer_status');

        $this->assertDatabaseHas(DealerRequest::class, ['email' => 'juan@example.com', 'city' => 'Cancún', 'source' => 'modal']);
        Mail::assertSent(DealerRequestReceived::class);
    }

    public function test_el_formulario_emergente_esta_en_todas_las_paginas(): void
    {
        $this->get('/')->assertSee('data-dealer-modal', false)->assertSee('Quiero ser Distribuidor KODU');
        $this->get('/martillos-hidraulicos')->assertSee('data-dealer-modal', false);
        $this->get('/distribuidores')->assertSee('¿Por qué ser distribuidor KODU?');
    }

    public function test_sitemap_incluye_productos(): void
    {
        $this->get('/sitemap.xml')
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/martillos-hidraulicos/kodu-53', false)
            ->assertSee('/distribuidores', false);
    }
}
