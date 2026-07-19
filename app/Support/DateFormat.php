<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class DateFormat
{
    private const MONTHS_ES = [
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre',
    ];

    public static function monthYear(Carbon $date): string
    {
        try {
            return $date->copy()->locale(app()->getLocale())->translatedFormat('F Y');
        } catch (\Throwable) {
            $month = self::MONTHS_ES[(int) $date->format('n')] ?? $date->format('F');

            return $month.' '.$date->format('Y');
        }
    }
}
