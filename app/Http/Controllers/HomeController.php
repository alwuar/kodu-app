<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // El carrusel muestra la primera categoría activa (Martillos hidráulicos)
        $category = Category::query()->active()->first();

        return view('pages.home', [
            'category' => $category,
            'products' => $category?->activeProducts()->with('category')->get() ?? collect(),
            'heroVideo' => Setting::get('hero_video'),
            'heroPoster' => Setting::get('hero_poster'),
        ]);
    }
}
