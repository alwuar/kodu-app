<?php

return [

    'name' => 'KODU',
    'legal_name' => 'KODU Attachments',
    'tagline' => 'Attachments to build',
    'description' => 'Martillos hidráulicos, accesorios y refacciones para maquinaria pesada. Tecnología mexicana fabricada para rendir, de 3 a 40 toneladas.',
    'locale' => 'es_MX',
    'theme_color' => '#262424',

    // A quién le llegan los avisos de pedidos, distribuidores y contacto
    'notify_email' => env('KODU_NOTIFY_EMAIL', 'ventas@koduattachments.mx'),

    'logo' => 'images/kodu-logo.svg',
    'og_image' => 'images/og-kodu.jpg',

    'contact' => [
        'email' => 'ventas@koduattachments.mx',
        'phone' => '999 234 1212',
        'phone_e164' => '+529992341212',
        // Botón "Habla con un asesor" (WhatsApp): número con lada de país, sin + ni espacios
        'whatsapp' => env('KODU_WHATSAPP', '529992341212'),
        'whatsapp_text' => 'Hola, me gustaría hablar con un asesor de KODU.',
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

    'hero' => [
        'kicker' => 'Aditamentos para maquinaria pesada',
        'title' => 'Aditamentos que aguantan la obra real',
        'text' => 'Martillos hidráulicos, accesorios y refacciones para maquinaria pesada — tecnología mexicana fabricada para rendir.',
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
            ],
        ],
    ],

    'dealers' => [
        'kicker' => 'Distribuidores',
        'title' => 'Vende KODU en tu zona',
        'text' => 'Sumamos distribuidores en todo México. Sin stock mínimo y sin altos volúmenes: tú vendes, nosotros te respaldamos con producto, refacciones y servicio técnico.',
        'why_title' => '¿Por qué ser distribuidor KODU?',
        'benefits' => [
            ['icon' => 'box', 'title' => 'Sin stock mínimo', 'text' => 'Empieza a vender sin invertir en inventario. Pide solo lo que ya tienes vendido.'],
            ['icon' => 'trending-up', 'title' => 'Márgenes competitivos', 'text' => 'Precios de distribuidor que te dejan ganancia real en cada martillo y accesorio.'],
            ['icon' => 'shield-check', 'title' => 'Producto que aguanta', 'text' => 'Aditamentos diseñados para la obra real, con garantía y respaldo de marca.'],
            ['icon' => 'wrench', 'title' => 'Refacciones y servicio', 'text' => 'Refacciones disponibles y servicio técnico especializado para tus clientes.'],
            ['icon' => 'graduation', 'title' => 'Capacitación', 'text' => 'Te enseñamos el producto, cómo venderlo y cómo instalarlo correctamente.'],
            ['icon' => 'megaphone', 'title' => 'Material de venta', 'text' => 'Fichas técnicas, fotos y material digital listo para promocionar en tu zona.'],
        ],
        'steps_title' => '¿Cómo funciona?',
        'steps' => [
            ['title' => 'Envía tu solicitud', 'text' => 'Llena el formulario con tus datos y los de tu empresa.'],
            ['title' => 'Platicamos', 'text' => 'Un asesor te contacta para conocer tu zona y tu operación.'],
            ['title' => 'Empieza a vender', 'text' => 'Recibes precios de distribuidor, material de venta y capacitación.'],
        ],
        'form_title' => 'Solicitud de distribuidor',
        'form_text' => 'Déjanos tus datos y te contactamos en menos de 48 horas hábiles.',
    ],

    'footer_text' => 'Aditamentos que aguantan la obra real. Marca mexicana, con desarrollo tecnológico en una de las fábricas más importantes del mundo.',

    'phone_countries' => [
        ['code' => 'MX', 'dial' => '+52'],
        ['code' => 'US', 'dial' => '+1'],
        ['code' => 'GT', 'dial' => '+502'],
        ['code' => 'BZ', 'dial' => '+501'],
    ],

];
