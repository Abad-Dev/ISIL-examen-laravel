<?php

namespace App\Support;

class Money
{
    public static function currency(): string
    {
        return config('money.currency', 'PEN');
    }

    public static function symbol(?string $currency = null): string
    {
        if ($currency !== null && $currency !== static::currency()) {
            return config("money.currencies.{$currency}.symbol", $currency);
        }

        return config('money.symbol', 'S/');
    }

    public static function format(float|string|int|null $amount, ?string $currency = null): string
    {
        if ($amount === null || $amount === '') {
            return static::symbol($currency).' 0.00';
        }

        $amount = (float) $amount;
        $locale = config('money.locale', 'es_PE');

        // Otras monedas usan su símbolo configurado para verse igual en todos los servidores.
        if ($currency !== null && $currency !== static::currency()) {
            return static::symbol($currency).' '.number_format($amount, 2, '.', ',');
        }

        if (class_exists(\NumberFormatter::class)) {
            try {
                $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
                $formatted = $formatter->formatCurrency($amount, static::currency());

                if ($formatted !== false) {
                    return $formatted;
                }
            } catch (\Throwable) {
                // Fallback when intl locale/currency is unavailable in production.
            }
        }

        return static::symbol().' '.number_format($amount, 2, '.', ',');
    }

    /**
     * Convierte un monto entre soles y otra moneda con un tipo de cambio en soles por unidad.
     */
    public static function convert(float|string $amount, string $from, string $to, float|string $tipoCambio): string
    {
        $amount = (float) $amount;
        $tipoCambio = (float) $tipoCambio;

        $converted = match (true) {
            $from === $to => $amount,
            $from === static::currency() => $amount / $tipoCambio,
            $to === static::currency() => $amount * $tipoCambio,
            default => $amount,
        };

        return number_format(round($converted, 2), 2, '.', '');
    }

    public static function add(float|string|int|null $left, float|string|int|null $right, int $scale = 2): string
    {
        return static::calculate('add', $left, $right, $scale);
    }

    public static function sub(float|string|int|null $left, float|string|int|null $right, int $scale = 2): string
    {
        return static::calculate('sub', $left, $right, $scale);
    }

    public static function mul(float|string|int|null $left, float|string|int|null $right, int $scale = 2): string
    {
        return static::calculate('mul', $left, $right, $scale);
    }

    private static function calculate(string $operation, float|string|int|null $left, float|string|int|null $right, int $scale): string
    {
        $left = static::normalizeAmount($left, $scale);
        $right = static::normalizeAmount($right, $scale);

        if ($operation === 'add' && \function_exists('bcadd')) {
            return \bcadd($left, $right, $scale);
        }

        if ($operation === 'sub' && \function_exists('bcsub')) {
            return \bcsub($left, $right, $scale);
        }

        if ($operation === 'mul' && \function_exists('bcmul')) {
            return \bcmul($left, $right, $scale);
        }

        $result = match ($operation) {
            'add' => (float) $left + (float) $right,
            'sub' => (float) $left - (float) $right,
            'mul' => (float) $left * (float) $right,
        };

        return number_format($result, $scale, '.', '');
    }

    private static function normalizeAmount(float|string|int|null $amount, int $scale): string
    {
        if ($amount === null || $amount === '') {
            return number_format(0, $scale, '.', '');
        }

        return number_format((float) $amount, $scale, '.', '');
    }
}
