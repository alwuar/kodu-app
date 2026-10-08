<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Tests\TestCase;

class SiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_paginas_principales_responden(): void
    {
        foreach (['/', '/martillos-hidraulicos', '/martillos-hidraulicos/kodu-53', '/accesorios', '/contacto', '/contacto?asunto=distribuidor', '/sitemap.xml'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }

    public function test_seo_basico_en_ficha_de_producto(): void
    {
        $this->get('/martillos-hidraulicos/kodu-53')
            ->assertSee('<title>KODU 53 — Martillo hidráulico | KODU</title>', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_producto_de_otra_categoria_da_404(): void
    {
        $this->get('/accesorios/kodu-53')->assertNotFound();
        $this->get('/martillos-hidraulicos/no-existe')->assertNotFound();
    }

    public function test_contacto_valida_campos(): void
    {
        $this->post('/contacto', [])
            ->assertRedirect()
            ->assertSessionHasErrors(['nombre', 'apellido', 'email', 'telefono']);
    }

    public function test_contacto_envia(): void
    {
        $this->post('/contacto', [
            'nombre' => 'Cecilia',
            'apellido' => 'Acevedo',
            'email' => 'cecy@example.com',
            'pais' => 'MX',
            'telefono' => '9991234567',
            'empresa' => 'Constructora',
            'asunto' => 'distribuidor',
        ])->assertRedirect('/contacto?asunto=distribuidor')
            ->assertSessionHas('status');
    }

    public function test_agregar_al_carrito(): void
    {
        $this->from('/martillos-hidraulicos/kodu-53')
            ->post('/carrito', ['product' => 'kodu-53'])
            ->assertRedirect('/martillos-hidraulicos/kodu-53')
            ->assertSessionHas('cart', ['kodu-53' => 1])
            ->assertSessionHas('cart_status');
    }
}
