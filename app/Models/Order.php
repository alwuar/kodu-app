<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    public const STATUSES = [
        'pending' => 'Pendiente de pago',
        'paid' => 'Pagado',
        'processing' => 'En preparación',
        'shipped' => 'Enviado',
        'delivered' => 'Entregado',
        'cancelled' => 'Cancelado',
        'failed' => 'Pago fallido',
    ];

    protected $fillable = [
        'number', 'status', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'subtotal', 'total', 'currency', 'stripe_session_id',
        'stripe_payment_intent', 'notes', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_address' => 'array',
            'subtotal' => 'integer',
            'total' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->number ??= 'K'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function formattedTotal(): string
    {
        return Money::format($this->total);
    }

    public function shippingLines(): array
    {
        $a = $this->shipping_address ?? [];

        return array_values(array_filter([
            $a['name'] ?? null,
            trim(($a['line1'] ?? '').' '.($a['line2'] ?? '')),
            trim(($a['postal_code'] ?? '').' '.($a['city'] ?? '').', '.($a['state'] ?? ''), ' ,'),
            $a['country'] ?? null,
        ]));
    }
}
