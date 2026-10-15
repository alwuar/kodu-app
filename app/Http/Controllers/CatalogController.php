<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $products = $category->activeProducts()->with('category')->get();

        return view('pages.catalog.index', [
            'category' => $category,
            'products' => $products,
            'filters' => $this->filters($products),
        ]);
    }

    public function show(Category $category, Product $product): View
    {
        abort_unless($category->is_active && $product->is_active, 404);

        $product->setRelation('category', $category);

        return view('pages.catalog.show', [
            'category' => $category,
            'product' => $product,
            'related' => $category->activeProducts()
                ->with('category')
                ->whereKeyNot($product->id)
                ->limit(3)
                ->get(),
        ]);
    }

    /** Solo muestra las opciones de filtro que tienen productos. */
    protected function filters(Collection $products): array
    {
        $tons = config('catalog.tons');

        return [
            'machine' => collect(config('catalog.machines'))
                ->only($products->pluck('machine')->filter()->unique()->all())
                ->all(),
            'box' => collect(config('catalog.boxes'))
                ->only($products->pluck('box')->filter()->unique()->all())
                ->all(),
            'tons' => [
                'min' => min($tons['min'], (int) ($products->min('tons_min') ?: $tons['min'])),
                'max' => max($tons['max'], (int) ($products->max('tons_max') ?: $tons['max'])),
            ],
        ];
    }
}
