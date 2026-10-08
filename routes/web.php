<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/contacto', [ContactController::class, 'show'])->name('contact');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');

Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');

Route::get('/{category}', [CatalogController::class, 'index'])
    ->whereIn('category', array_keys(config('catalog.categories')))
    ->name('catalog.index');

Route::get('/{category}/{product}', [CatalogController::class, 'show'])
    ->whereIn('category', array_keys(config('catalog.categories')))
    ->name('catalog.show');
