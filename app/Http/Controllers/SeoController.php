<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $categories = Category::query()->active()->get();

        $urls = collect([route('home'), route('dealers'), route('contact')])
            ->merge($categories->map->url())
            ->merge(
                Product::query()
                    ->active()
                    ->whereIn('category_id', $categories->pluck('id'))
                    ->with('category')
                    ->get()
                    ->map->url()
            );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls->map(fn (string $url) => '  <url><loc>'.e($url).'</loc></url>')->implode("\n")."\n"
            .'</urlset>'."\n";

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /carrito', 'Disallow: /checkout', 'Disallow: /pedido', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
