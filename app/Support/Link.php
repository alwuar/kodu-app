<?php

namespace App\Support;

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Throwable;

class Link
{
    /**
     * Arma la URL de un enlace definido en config/kodu.php.
     * Si apunta a una categoría que ya no existe, manda al inicio.
     */
    public static function to(array $item): string
    {
        if (! Route::has($item['route'])) {
            return url('/');
        }

        try {
            $url = route($item['route'], $item['params'] ?? []);
        } catch (Throwable) {
            return url('/');
        }

        if (! empty($item['query'])) {
            $url .= '?'.http_build_query($item['query']);
        }

        if (! empty($item['fragment'])) {
            $url .= '#'.$item['fragment'];
        }

        return $url;
    }

    /**
     * Menú principal: páginas fijas + categorías marcadas "mostrar en menú".
     *
     * @return array<int, array{label: string, url: string, active: bool}>
     */
    public static function menu(bool $footer = false): array
    {
        $current = request()->route();
        $routeName = $current?->getName();
        $routeCategory = $current?->parameter('category');
        $categorySlug = $routeCategory instanceof Category ? $routeCategory->slug : $routeCategory;

        $items = [
            ['label' => 'Inicio', 'url' => route('home'), 'active' => $routeName === 'home'],
            ['label' => 'Nosotros', 'url' => route('home').'#nosotros', 'active' => false],
        ];

        foreach (static::menuCategories() as $category) {
            $items[] = [
                'label' => $footer ? $category->name : ucfirst(mb_strtolower($category->name)),
                'url' => $category->url(),
                'active' => str_starts_with((string) $routeName, 'catalog.') && $categorySlug === $category->slug,
            ];
        }

        $items[] = ['label' => 'Distribuidores', 'url' => route('dealers'), 'active' => $routeName === 'dealers'];
        $items[] = ['label' => 'Contacto', 'url' => route('contact'), 'active' => $routeName === 'contact'];

        return $items;
    }

    protected static function menuCategories()
    {
        return once(function () {
            try {
                return Category::query()->active()->where('show_in_menu', true)->get();
            } catch (Throwable) {
                return collect();
            }
        });
    }
}
