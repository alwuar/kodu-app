<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::get('/contacto', [ContactController::class, 'show'])->name('contact');
Route::post('/contacto', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

Route::get('/distribuidores', [DealerController::class, 'show'])->name('dealers');
Route::post('/distribuidores', [DealerController::class, 'store'])->middleware('throttle:10,1')->name('dealers.store');

// Carrito y pago
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('throttle:10,1')->name('checkout');
Route::get('/pedido/{order}/confirmacion', [CheckoutController::class, 'success'])->name('checkout.success');

Route::post('/stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

// Panel de administración
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');

        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('categorias', Admin\CategoryController::class)
            ->except('show')
            ->parameters(['categorias' => 'category'])
            ->names('categories');

        Route::resource('productos', Admin\ProductController::class)
            ->except('show')
            ->parameters(['productos' => 'product'])
            ->names('products');

        Route::get('pedidos', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('pedidos/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('pedidos/{order}', [Admin\OrderController::class, 'update'])->name('orders.update');

        Route::get('distribuidores', [Admin\DealerRequestController::class, 'index'])->name('dealers.index');
        Route::get('distribuidores/{dealer}', [Admin\DealerRequestController::class, 'show'])->name('dealers.show');
        Route::patch('distribuidores/{dealer}', [Admin\DealerRequestController::class, 'update'])->name('dealers.update');
        Route::delete('distribuidores/{dealer}', [Admin\DealerRequestController::class, 'destroy'])->name('dealers.destroy');

        Route::get('mensajes', [Admin\MessageController::class, 'index'])->name('messages.index');
        Route::get('mensajes/{message}', [Admin\MessageController::class, 'show'])->name('messages.show');
        Route::delete('mensajes/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('inicio', [Admin\HomeSettingsController::class, 'edit'])->name('home.edit');
        Route::put('inicio', [Admin\HomeSettingsController::class, 'update'])->name('home.update');
    });
});

// Catálogo (al final para no tapar las rutas anteriores)
Route::get('/{category}', [CatalogController::class, 'index'])
    ->where('category', '[a-z0-9-]+')
    ->name('catalog.index');

Route::get('/{category}/{product:slug}', [CatalogController::class, 'show'])
    ->where('category', '[a-z0-9-]+')
    ->scopeBindings()
    ->name('catalog.show');
