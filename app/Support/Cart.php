<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * Carrito guardado en la sesión: [product_id => cantidad].
 */
class Cart
{
    public const MAX_QUANTITY = 20;

    protected const KEY = 'cart';

    public function items(): array
    {
        return session(self::KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->items();
        $items[$product->id] = min(self::MAX_QUANTITY, ($items[$product->id] ?? 0) + max(1, $quantity));
        session([self::KEY => $items]);
    }

    public function update(int $productId, int $quantity): void
    {
        $items = $this->items();

        if ($quantity < 1) {
            unset($items[$productId]);
        } elseif (isset($items[$productId])) {
            $items[$productId] = min(self::MAX_QUANTITY, $quantity);
        }

        session([self::KEY => $items]);
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        session()->forget(self::KEY);
    }

    public function count(): int
    {
        return array_sum($this->items());
    }

    /**
     * Renglones del carrito con el producto, cantidad y totales.
     * Quita los productos que ya no existen o se desactivaron.
     *
     * @return Collection<int, array{product: Product, quantity: int, unit_price: int, total: int}>
     */
    public function lines(): Collection
    {
        $items = $this->items();

        if (! $items) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->whereIn('id', array_keys($items))
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $lines = collect($items)
            ->filter(fn ($qty, $id) => $products->has($id))
            ->map(fn ($qty, $id) => [
                'product' => $products[$id],
                'quantity' => (int) $qty,
                'unit_price' => $products[$id]->price,
                'total' => $products[$id]->price * (int) $qty,
            ])
            ->values();

        if ($lines->count() !== count($items)) {
            session([self::KEY => $lines->mapWithKeys(fn ($l) => [$l['product']->id => $l['quantity']])->all()]);
        }

        return $lines;
    }

    public function subtotal(?Collection $lines = null): int
    {
        return ($lines ?? $this->lines())->sum('total');
    }
}
