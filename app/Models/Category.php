<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** Slugs que ya usan otras páginas del sitio. */
    public const RESERVED_SLUGS = [
        'admin', 'carrito', 'checkout', 'contacto', 'distribuidores', 'pedido',
        'stripe', 'sitemap.xml', 'robots.txt', 'storage', 'build', 'images', 'up',
    ];

    protected $fillable = [
        'name', 'slug', 'singular', 'description', 'seo_title', 'seo_description',
        'show_in_menu', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'show_in_menu' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function url(): string
    {
        return route('catalog.index', $this);
    }

    public function singularName(): string
    {
        return $this->singular ?: $this->name;
    }
}
