<?php

namespace App\Support;

use Illuminate\Support\Collection;

class Catalog
{
    public static function categories(): Collection
    {
        return collect(config('catalog.categories'))
            ->map(fn (array $category, string $slug) => ['slug' => $slug] + $category);
    }

    public static function category(string $slug): ?array
    {
        return static::categories()->get($slug);
    }

    public static function products(?string $category = null): Collection
    {
        return collect(config('catalog.products'))
            ->map(fn (array $product, string $slug) => static::hydrate($slug, $product))
            ->when($category, fn (Collection $items) => $items->where('category', $category))
            ->values();
    }

    public static function product(string $category, string $slug): ?array
    {
        $product = config("catalog.products.{$slug}");

        if (! $product || $product['category'] !== $category) {
            return null;
        }

        return static::hydrate($slug, $product);
    }

    public static function related(array $product, int $limit = 3): Collection
    {
        return static::products($product['category'])
            ->reject(fn (array $item) => $item['slug'] === $product['slug'])
            ->take($limit)
            ->values();
    }

    public static function filters(Collection $products): array
    {
        $filters = config('catalog.filters');

        return [
            'machine' => collect($filters['machine'])
                ->only($products->pluck('machine')->filter()->unique()->all())
                ->all(),
            'box' => collect($filters['box'])
                ->only($products->pluck('box')->filter()->unique()->all())
                ->all(),
            'tons' => $filters['tons'],
        ];
    }

    protected static function hydrate(string $slug, array $product): array
    {
        $product['slug'] = $slug;
        $product['url'] = route('catalog.show', ['category' => $product['category'], 'product' => $slug]);

        return $product;
    }
}
