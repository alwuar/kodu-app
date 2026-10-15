<?php

namespace App\Support;

class Money
{
    /** Formatea centavos como "$1,250.00 MXN". */
    public static function format(int $cents, string $currency = 'MXN'): string
    {
        return '$'.number_format($cents / 100, 2).' '.$currency;
    }

    /** Convierte lo que se escribe en el panel ("1,250.50") a centavos. */
    public static function toCents(string|int|float|null $value): int
    {
        $clean = preg_replace('/[^\d.]/', '', (string) $value);

        return (int) round(((float) $clean) * 100);
    }

    public static function toInput(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }
}
