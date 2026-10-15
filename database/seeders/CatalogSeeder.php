<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * Catálogo inicial de la maqueta. Los precios son de ejemplo (centavos MXN,
 * IVA incluido) y se cambian desde el panel de administración.
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'martillos-hidraulicos',
                'name' => 'Martillos Hidráulicos',
                'singular' => 'Martillo hidráulico',
                'description' => 'Para minicargador, miniexcavadora, retroexcavadora y excavadoras desde 3 hasta 40 toneladas. Consulta la ficha técnica de cada modelo — el precio se muestra al agregar al carrito.',
                'seo_title' => 'Martillos hidráulicos de 3 a 40 toneladas',
                'seo_description' => 'Martillos hidráulicos KODU para minicargador, miniexcavadora, retroexcavadora y excavadora de 3 a 40 toneladas, en caja abierta y cerrada.',
                'sort' => 0,
            ],
            [
                'slug' => 'accesorios',
                'name' => 'Accesorios',
                'singular' => 'Accesorio',
                'description' => 'Todo lo que tu maquinaria necesita para rendir más, seleccionado para trabajar tan duro como tú. Consulta la ficha técnica de cada modelo — el precio se muestra al agregar al carrito.',
                'seo_title' => 'Accesorios y aditamentos para maquinaria pesada',
                'seo_description' => 'Accesorios y aditamentos KODU para minicargador, miniexcavadora, retroexcavadora y excavadora.',
                'sort' => 1,
            ],
        ];

        foreach ($categories as $data) {
            Category::query()->updateOrCreate(['slug' => $data['slug']], $data);
        }

        $products = [
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-53',
                'name' => 'KODU 53',
                'sku' => 'KODU-53',
                'kicker' => 'Martillo hidráulico · Caja cerrada',
                'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 12 a 18 toneladas. Potencia y precisión para demolición y corte en obra.',
                'price' => 18950000,
                'image' => 'images/productos/kodu-53.webp',
                'datasheet' => 'images/fichas/kodu-53.pdf',
                'machine' => 'minicargador',
                'tons_min' => 3,
                'tons_max' => 5,
                'box' => 'abierta',
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
                'sort' => 0,
            ],
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-68',
                'name' => 'KODU 68',
                'sku' => 'KODU-68',
                'kicker' => 'Martillo hidráulico · Caja abierta',
                'summary' => 'Martillo hidráulico de caja abierta para miniexcavadoras de 4 a 7 toneladas. Ligero, preciso y fácil de mantener.',
                'price' => 6890000,
                'image' => 'images/productos/kodu-53.webp',
                'machine' => 'miniexcavadora',
                'tons_min' => 4,
                'tons_max' => 7,
                'box' => 'abierta',
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
                'sort' => 1,
            ],
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-75',
                'name' => 'KODU 75',
                'sku' => 'KODU-75',
                'kicker' => 'Martillo hidráulico · Caja cerrada',
                'summary' => 'Martillo hidráulico de caja cerrada para retroexcavadoras de 6 a 9 toneladas. Menos ruido y mayor protección del cuerpo.',
                'price' => 9450000,
                'image' => 'images/productos/kodu-53.webp',
                'machine' => 'retroexcavadora',
                'tons_min' => 6,
                'tons_max' => 9,
                'box' => 'cerrada',
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
                'sort' => 2,
            ],
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-100',
                'name' => 'KODU 100',
                'sku' => 'KODU-100',
                'kicker' => 'Martillo hidráulico · Caja cerrada',
                'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 10 a 15 toneladas. Rendimiento constante en roca y concreto.',
                'price' => 14800000,
                'image' => 'images/productos/kodu-53.webp',
                'machine' => 'excavadora',
                'tons_min' => 10,
                'tons_max' => 15,
                'box' => 'cerrada',
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
                'sort' => 3,
            ],
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-135',
                'name' => 'KODU 135',
                'sku' => 'KODU-135',
                'kicker' => 'Martillo hidráulico · Caja cerrada',
                'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 18 a 25 toneladas. Diseñado para demolición pesada y canteras.',
                'price' => 24500000,
                'image' => 'images/productos/kodu-53.webp',
                'machine' => 'excavadora',
                'tons_min' => 18,
                'tons_max' => 25,
                'box' => 'cerrada',
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
                'sort' => 4,
            ],
            [
                'category' => 'martillos-hidraulicos',
                'slug' => 'kodu-155',
                'name' => 'KODU 155',
                'sku' => 'KODU-155',
                'kicker' => 'Martillo hidráulico · Caja cerrada',
                'summary' => 'Martillo hidráulico de caja cerrada para excavadoras de 28 a 40 toneladas. La máxima energía de impacto de la línea.',
                'price' => 38900000,
                'image' => 'images/productos/kodu-53.webp',
                'machine' => 'excavadora',
                'tons_min' => 28,
                'tons_max' => 40,
                'box' => 'cerrada',
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
                'sort' => 5,
            ],
            [
                'category' => 'accesorios',
                'slug' => 'cucharon-excavacion',
                'name' => 'Cucharón de excavación',
                'sku' => 'CUCHARON-EXCAVACION',
                'kicker' => 'Accesorio · Excavación',
                'summary' => 'Cucharón de uso general con dientes reemplazables para excavadoras de 10 a 25 toneladas.',
                'price' => 4250000,
                'image' => null,
                'machine' => 'excavadora',
                'tons_min' => 10,
                'tons_max' => 25,
                'box' => null,
                'specs' => [
                    'Máquina compatible' => 'Excavadora 10–25 ton',
                    'Capacidad' => '0.6–1.2 m³',
                    'Material' => 'Acero antidesgaste',
                    'Documentos' => 'Ficha técnica en PDF',
                ],
                'sort' => 6,
            ],
            [
                'category' => 'accesorios',
                'slug' => 'cucharon-zanjeo',
                'name' => 'Cucharón de zanjeo',
                'sku' => 'CUCHARON-ZANJEO',
                'kicker' => 'Accesorio · Zanjeo',
                'summary' => 'Cucharón angosto para zanjas limpias en instalaciones hidráulicas y eléctricas.',
                'price' => 1890000,
                'image' => null,
                'machine' => 'miniexcavadora',
                'tons_min' => 3,
                'tons_max' => 8,
                'box' => null,
                'specs' => [
                    'Máquina compatible' => 'Miniexcavadora 3–8 ton',
                    'Ancho' => '300–450 mm',
                    'Material' => 'Acero antidesgaste',
                    'Documentos' => 'Ficha técnica en PDF',
                ],
                'sort' => 7,
            ],
            [
                'category' => 'accesorios',
                'slug' => 'enganche-rapido',
                'name' => 'Enganche rápido',
                'sku' => 'ENGANCHE-RAPIDO',
                'kicker' => 'Accesorio · Acoplamiento',
                'summary' => 'Cambia de aditamento en segundos sin bajar de la cabina.',
                'price' => 3600000,
                'image' => null,
                'machine' => 'retroexcavadora',
                'tons_min' => 6,
                'tons_max' => 9,
                'box' => null,
                'specs' => [
                    'Máquina compatible' => 'Retroexcavadora 6–9 ton',
                    'Accionamiento' => 'Hidráulico',
                    'Documentos' => 'Ficha técnica en PDF',
                ],
                'sort' => 8,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::query()->where('slug', $data['category'])->firstOrFail();
            unset($data['category']);

            // Se guarda como lista para conservar el orden (MySQL reordena las llaves de un objeto JSON)
            $data['specs'] = collect($data['specs'])
                ->map(fn ($value, $label) => ['label' => $label, 'value' => $value])
                ->values()
                ->all();

            Product::query()->updateOrCreate(
                ['category_id' => $category->id, 'slug' => $data['slug']],
                $data + ['category_id' => $category->id],
            );
        }
    }
}
