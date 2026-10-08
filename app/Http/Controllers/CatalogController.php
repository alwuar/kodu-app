<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(string $category): View
    {
        $categoryData = Catalog::category($category) ?? abort(404);
        $products = Catalog::products($category);

        return view('pages.catalog.index', [
            'category' => $categoryData,
            'products' => $products,
            'filters' => Catalog::filters($products),
        ]);
    }

    public function show(string $category, string $product): View
    {
        $categoryData = Catalog::category($category) ?? abort(404);
        $productData = Catalog::product($category, $product) ?? abort(404);

        return view('pages.catalog.show', [
            'category' => $categoryData,
            'product' => $productData,
            'related' => Catalog::related($productData),
        ]);
    }
}
