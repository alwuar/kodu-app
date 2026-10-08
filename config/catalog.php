<?php

/*
|--------------------------------------------------------------------------
| Catálogo
|--------------------------------------------------------------------------
| Contenido de ejemplo para la maqueta. Cada producto pertenece a una
| categoría; los filtros del listado se arman con los atributos
| `machine`, `tons` y `box`. Si `image` es null, o el archivo no existe
| en /public, se muestra un rectángulo gris en su lugar.
*/

return [

    'filters' => [
        'machine' => [
            'minicargador' => 'Minicargador',
            'miniexcavadora' => 'Miniexcavadora',
            'retroexcavadora' => 'Retroexcavadora',
            'excavadora' => 'Excavadora',
        ],
        'box' => [
            'abierta' => 'Caja abierta',
            'cerrada' => 'Caja cerrada',
        ],
        'tons' => ['min' => 3, 'max' => 40],
    ],

    'categories' => [

        'martillos-hidraulicos' => [
            'name' => 'Martillos Hidráulicos',
            'singular' => 'Martillo hidráulico',
            'description' => 'Para minicargador, miniexcavadora, retroexcavadora y excavadoras desde 3 hasta 40 toneladas. Consulta la ficha técnica de cada modelo — el precio se muestra al agregar al carrito.',
            'seo_title' => 'Martillos hidráulicos de 3 a 40 toneladas',
            'seo_description' => 'Martillos hidráulicos KODU para minicargador, miniexcavadora, retroexcavadora y excavadora de 3 a 40 toneladas, en caja abierta y cerrada.',
        ],

        'accesorios' => [
            'name' => 'Accesorios',
            'singular' => 'Accesorio',
            'description' => 'Todo lo que tu maquinaria necesita para rendir más, seleccionado para trabajar tan duro como tú. Consulta la ficha técnica de cada modelo — el precio se muestra al agregar al carrito.',
            'seo_title' => 'Accesorios y aditamentos para maquinaria pesada',
            'seo_description' => 'Accesorios y aditamentos KODU para minicargador, miniexcavadora, retroexcavadora y excavadora.',
        ],

    ],

    'products' => [

        'kodu-53' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 53',
            'kicker' => 'Martillo hidráulico · Caja cerrada',
            'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 12 a 18 toneladas. Potencia y precisión para demolición y corte en obra.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'minicargador',
            'tons' => [3, 5],
            'box' => 'abierta',
            'tags' => ['Mini cargador', '3 - 5 ton', 'Caja abierta'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Excavadora 12–18 ton',
                'Tipo de caja' => 'Cerrada',
                'Peso operativo' => '1,250 kg',
                'Frecuencia de impacto' => '380–620 golpes/min',
                'Presión de trabajo' => '160 bar',
                'Diámetro de pistón' => '125 mm',
                'Consumo de aceite' => '140–210 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'kodu-68' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 68',
            'kicker' => 'Martillo hidráulico · Caja abierta',
            'summary' => 'Martillo hidráulico de caja abierta para miniexcavadoras de 4 a 7 toneladas. Ligero, preciso y fácil de mantener.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'miniexcavadora',
            'tons' => [4, 7],
            'box' => 'abierta',
            'tags' => ['Miniexcavadora', '4 - 7 ton', 'Caja abierta'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Miniexcavadora 4–7 ton',
                'Tipo de caja' => 'Abierta',
                'Peso operativo' => '310 kg',
                'Frecuencia de impacto' => '500–900 golpes/min',
                'Presión de trabajo' => '120 bar',
                'Diámetro de pistón' => '68 mm',
                'Consumo de aceite' => '40–70 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'kodu-75' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 75',
            'kicker' => 'Martillo hidráulico · Caja cerrada',
            'summary' => 'Martillo hidráulico de caja cerrada para retroexcavadoras de 6 a 9 toneladas. Menos ruido y mayor protección del cuerpo.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'retroexcavadora',
            'tons' => [6, 9],
            'box' => 'cerrada',
            'tags' => ['Retroexcavadora', '6 - 9 ton', 'Caja cerrada'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Retroexcavadora 6–9 ton',
                'Tipo de caja' => 'Cerrada',
                'Peso operativo' => '480 kg',
                'Frecuencia de impacto' => '450–850 golpes/min',
                'Presión de trabajo' => '130 bar',
                'Diámetro de pistón' => '75 mm',
                'Consumo de aceite' => '50–90 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'kodu-100' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 100',
            'kicker' => 'Martillo hidráulico · Caja cerrada',
            'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 10 a 15 toneladas. Rendimiento constante en roca y concreto.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'excavadora',
            'tons' => [10, 15],
            'box' => 'cerrada',
            'tags' => ['Excavadora', '10 - 15 ton', 'Caja cerrada'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Excavadora 10–15 ton',
                'Tipo de caja' => 'Cerrada',
                'Peso operativo' => '980 kg',
                'Frecuencia de impacto' => '400–700 golpes/min',
                'Presión de trabajo' => '150 bar',
                'Diámetro de pistón' => '100 mm',
                'Consumo de aceite' => '90–140 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'kodu-135' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 135',
            'kicker' => 'Martillo hidráulico · Caja cerrada',
            'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 18 a 25 toneladas. Diseñado para demolición pesada y canteras.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'excavadora',
            'tons' => [18, 25],
            'box' => 'cerrada',
            'tags' => ['Excavadora', '18 - 25 ton', 'Caja cerrada'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Excavadora 18–25 ton',
                'Tipo de caja' => 'Cerrada',
                'Peso operativo' => '1,700 kg',
                'Frecuencia de impacto' => '350–600 golpes/min',
                'Presión de trabajo' => '170 bar',
                'Diámetro de pistón' => '135 mm',
                'Consumo de aceite' => '150–230 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'kodu-155' => [
            'category' => 'martillos-hidraulicos',
            'name' => 'KODU 155',
            'kicker' => 'Martillo hidráulico · Caja cerrada',
            'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 28 a 40 toneladas. La máxima energía de impacto de la línea.',
            'image' => 'images/productos/kodu-53.webp',
            'thumb' => 'images/productos/kodu-53-sm.webp',
            'machine' => 'excavadora',
            'tons' => [28, 40],
            'box' => 'cerrada',
            'tags' => ['Excavadora', '28 - 40 ton', 'Caja cerrada'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Excavadora 28–40 ton',
                'Tipo de caja' => 'Cerrada',
                'Peso operativo' => '2,900 kg',
                'Frecuencia de impacto' => '300–500 golpes/min',
                'Presión de trabajo' => '180 bar',
                'Diámetro de pistón' => '155 mm',
                'Consumo de aceite' => '200–260 l/min',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'cucharon-excavacion' => [
            'category' => 'accesorios',
            'name' => 'Cucharón de excavación',
            'kicker' => 'Accesorio · Excavación',
            'summary' => 'Cucharón de uso general con dientes reemplazables para excavadoras de 10 a 25 toneladas.',
            'image' => null,
            'thumb' => null,
            'machine' => 'excavadora',
            'tons' => [10, 25],
            'box' => null,
            'tags' => ['Excavadora', '10 - 25 ton'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Excavadora 10–25 ton',
                'Capacidad' => '0.6–1.2 m³',
                'Material' => 'Acero antidesgaste',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'cucharon-zanjeo' => [
            'category' => 'accesorios',
            'name' => 'Cucharón de zanjeo',
            'kicker' => 'Accesorio · Zanjeo',
            'summary' => 'Cucharón angosto para zanjas limpias en instalaciones hidráulicas y eléctricas.',
            'image' => null,
            'thumb' => null,
            'machine' => 'miniexcavadora',
            'tons' => [3, 8],
            'box' => null,
            'tags' => ['Miniexcavadora', '3 - 8 ton'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Miniexcavadora 3–8 ton',
                'Ancho' => '300–450 mm',
                'Material' => 'Acero antidesgaste',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

        'enganche-rapido' => [
            'category' => 'accesorios',
            'name' => 'Enganche rápido',
            'kicker' => 'Accesorio · Acoplamiento',
            'summary' => 'Cambia de aditamento en segundos sin bajar de la cabina.',
            'image' => null,
            'thumb' => null,
            'machine' => 'retroexcavadora',
            'tons' => [6, 9],
            'box' => null,
            'tags' => ['Retroexcavadora', '6 - 9 ton'],
            'price' => null,
            'datasheet' => null,
            'specs' => [
                'Máquina compatible' => 'Retroexcavadora 6–9 ton',
                'Accionamiento' => 'Hidráulico',
                'Documentos' => 'Ficha técnica en PDF',
            ],
        ],

    ],

];
