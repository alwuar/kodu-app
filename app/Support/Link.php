<?php

namespace App\Support;

class Link
{
    /**
     * Arma la URL de un elemento de navegación definido en config/kodu.php.
     */
    public static function to(array $item): string
    {
        $url = route($item['route'], $item['params'] ?? []);

        if (! empty($item['query'])) {
            $url .= '?'.http_build_query($item['query']);
        }

        if (! empty($item['fragment'])) {
            $url .= '#'.$item['fragment'];
        }

        return $url;
    }

    public static function active(array $item): bool
    {
        if (! empty($item['fragment'])) {
            return false;
        }

        if (! request()->routeIs($item['route'], $item['route'].'.*') && ! static::sameCatalog($item)) {
            return false;
        }

        foreach ($item['params'] ?? [] as $key => $value) {
            if (request()->route($key) !== $value) {
                return false;
            }
        }

        foreach ($item['query'] ?? [] as $key => $value) {
            if (request()->query($key) !== $value) {
                return false;
            }
        }

        if ($item['route'] === 'contact' && empty($item['query'])) {
            return request()->query('asunto') !== 'distribuidor';
        }

        return true;
    }

    protected static function sameCatalog(array $item): bool
    {
        return $item['route'] === 'catalog.index' && request()->routeIs('catalog.show');
    }
}
