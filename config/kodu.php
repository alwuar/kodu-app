<?php

return [

    'name' => 'KODU',
    'legal_name' => 'KODU Attachments',
    'tagline' => 'Attachments to build',
    'description' => 'Martillos hidráulicos, accesorios y refacciones para maquinaria pesada. Tecnología mexicana fabricada para rendir, de 3 a 40 toneladas.',
    'locale' => 'es_MX',
    'theme_color' => '#262424',

    'logo' => 'images/kodu-logo.svg',
    'og_image' => 'images/og-kodu.jpg',

    'contact' => [
        'email' => 'ventas@koduattachments.mx',
        'phone' => '999 234 1212',
        'phone_e164' => '+529992341212',
        'address' => 'Col. Bojórquez, Mérida, Yuc.',
        'locality' => 'Mérida',
        'region' => 'Yucatán',
        'country' => 'MX',
    ],

    'social' => [
        'facebook' => 'https://www.facebook.com/',
        'instagram' => 'https://www.instagram.com/',
    ],

    'coverage' => ['Playa del Carmen', 'Mérida', 'Puerto Morelos', 'Pachuca'],

    'nav' => [
        ['label' => 'Inicio', 'route' => 'home'],
        ['label' => 'Nosotros', 'route' => 'home', 'fragment' => 'nosotros'],
        ['label' => 'Martillos hidráulicos', 'route' => 'catalog.index', 'params' => ['category' => 'martillos-hidraulicos']],
        ['label' => 'Accesorios', 'route' => 'catalog.index', 'params' => ['category' => 'accesorios']],
        ['label' => 'Distribuidores', 'route' => 'contact', 'query' => ['asunto' => 'distribuidor']],
        ['label' => 'Contacto', 'route' => 'contact'],
    ],

    'footer_nav' => [
        ['label' => 'Inicio', 'route' => 'home'],
        ['label' => 'Nosotros', 'route' => 'home', 'fragment' => 'nosotros'],
        ['label' => 'Martillos Hidráulicos', 'route' => 'catalog.index', 'params' => ['category' => 'martillos-hidraulicos']],
        ['label' => 'Distribuidores', 'route' => 'contact', 'query' => ['asunto' => 'distribuidor']],
        ['label' => 'Contacto', 'route' => 'contact'],
    ],

    'hero' => [
        'kicker' => 'Aditamentos para maquinaria pesada',
        'title' => 'Aditamentos que aguantan la obra real',
        'text' => 'Martillos hidráulicos, accesorios y refacciones para maquinaria pesada — tecnología mexicana fabricada para rendir.',
        'image' => null,
    ],

    'why' => [
        'title' => 'Por qué Kodú',
        'items' => [
            ['icon' => 'shield-check', 'title' => 'Exigencias reales', 'text' => 'Aditamentos diseñados para las exigencias reales de la obra, no de catálogo.'],
            ['icon' => 'circle-arrow', 'title' => '3 a 40 toneladas', 'text' => 'Compatibilidad desde minicargadores hasta excavadoras de 40 toneladas.'],
            ['icon' => 'puzzle', 'title' => 'Servicio técnico', 'text' => 'Refacciones y servicio especializado que extienden la vida útil de tu equipo.'],
        ],
    ],

    'dealer_cta' => [
        'kicker' => 'Aquí empieza tu camino al éxito',
        'title' => '¿Quieres vender KODU en tu zona?',
        'text' => 'Sin stock mínimo. Sin altos volúmenes. Solo contáctanos y te decimos cómo.',
        'button' => 'Iniciar Solicitud',
    ],

    'services' => [
        'title' => 'Nuestros servicios',
        'items' => [
            [
                'icon' => 'edit',
                'title' => 'Martillos hidráulicos',
                'text' => 'Potencia y precisión para cada corte, de 3 a 40 toneladas, en caja abierta y cerrada.',
                'link' => 'Ver martillos',
                'route' => 'catalog.index',
                'params' => ['category' => 'martillos-hidraulicos'],
            ],
            [
                'icon' => 'download',
                'title' => 'Accesorios y Aditamentos',
                'text' => 'Todo lo que tu maquinaria necesita para rendir más, seleccionado para trabajar tan duro como tú.',
                'link' => 'Ver accesorios',
                'route' => 'catalog.index',
                'params' => ['category' => 'accesorios'],
            ],
            [
                'icon' => 'refresh',
                'title' => 'Refacciones y Servicio Técnico',
                'text' => 'Amplia gama de refacciones para distintos modelos y marcas, con servicio técnico especializado.',
                'link' => 'Ver refacciones',
                'route' => 'contact',
                'query' => ['asunto' => 'refacciones'],
            ],
        ],
    ],

    'footer_text' => 'Aditamentos que aguantan la obra real. Marca mexicana, con desarrollo tecnológico en una de las fábricas más importantes del mundo.',

    'phone_countries' => [
        ['code' => 'MX', 'dial' => '+52'],
        ['code' => 'US', 'dial' => '+1'],
        ['code' => 'GT', 'dial' => '+502'],
        ['code' => 'BZ', 'dial' => '+501'],
    ],

];
