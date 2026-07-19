<?php

namespace App\Support;

class Money
{
    public static function currency(): string
    {
        return config('money.currency', 'PEN');
    }

    public static function symbol(): string
    {
        return config('money.symbol', 'S/');
    }

    public static function format(float|string|int|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return static::symbol().' 0.00';
        }

        $amount = (float) $amount;
        $locale = config('money.locale', 'es_PE');

        if (class_exists(\NumberFormatter::class)) {
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            $formatted = $formatter->formatCurrency($amount, static::currency());

            if ($formatted !== false) {
                return $formatted;
            }
        }

        return static::symbol().' '.number_format($amount, 2, '.', ',');
    }
}
