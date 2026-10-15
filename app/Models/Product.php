<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'kicker', 'summary', 'price',
        'image', 'datasheet', 'machine', 'tons_min', 'tons_max', 'box',
        'specs', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'specs' => 'array',
            'is_active' => 'boolean',
            'price' => 'integer',
            'tons_min' => 'integer',
            'tons_max' => 'integer',
            'sort' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort')->orderBy('id');
    }

    public function url(): string
    {
        return route('catalog.show', [$this->category->slug, $this->slug]);
    }

    /**
     * Ruta relativa a /public para <x-ui.image>. Las imágenes subidas desde el
     * panel viven en el disco "public" (storage/app/public).
     */
    public function imagePath(): ?string
    {
        return static::publicPath($this->image);
    }

    public function datasheetUrl(): ?string
    {
        $path = static::publicPath($this->datasheet);

        return $path && is_file(public_path($path)) ? asset($path) : null;
    }

    public static function publicPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'images/') ? $path : 'storage/'.$path;
    }

    /** Etiquetas de la tarjeta, armadas con los filtros del producto. */
    public function tags(): array
    {
        $tags = [];

        if ($this->machine) {
            $tags[] = config('catalog.machines.'.$this->machine, $this->machine);
        }

        if ($this->tons_min && $this->tons_max) {
            $tags[] = $this->tons_min.' - '.$this->tons_max.' ton';
        } elseif ($this->tons_min || $this->tons_max) {
            $tags[] = ($this->tons_min ?: $this->tons_max).' ton';
        }

        if ($this->box) {
            $tags[] = config('catalog.boxes.'.$this->box, $this->box);
        }

        return $tags;
    }

    public function kickerText(): string
    {
        if ($this->kicker) {
            return $this->kicker;
        }

        $parts = [$this->category->singularName()];

        if ($this->box) {
            $parts[] = config('catalog.boxes.'.$this->box);
        }

        return implode(' · ', array_filter($parts));
    }

    public function formattedPrice(): string
    {
        return Money::format($this->price);
    }

    public function isPurchasable(): bool
    {
        return $this->is_active && $this->price > 0;
    }
}
