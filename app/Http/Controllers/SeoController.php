<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect([route('home'), route('contact')])
            ->merge(Catalog::categories()->keys()->map(fn (string $slug) => route('catalog.index', $slug)))
            ->merge(Catalog::products()->pluck('url'));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls->map(fn (string $url) => '  <url><loc>'.e($url).'</loc></url>')->implode("\n")."\n"
            .'</urlset>'."\n";

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = app()->isProduction()
            ? ['User-agent: *', 'Disallow: /carrito', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
