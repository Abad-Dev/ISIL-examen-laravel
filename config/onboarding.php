<?php

return [
    'cuentas' => [
        [
            'nombre' => 'Billetera',
            'tipo' => 'efectivo',
            'saldo' => 0,
            'icon' => 'heroicon-o-wallet',
            'color_hex' => '#8DDA90',
        ],
        [
            'nombre' => 'Yape',
            'tipo' => 'billetera_digital',
            'saldo' => 0,
            'icon' => 'heroicon-o-device-phone-mobile',
            'color_hex' => '#A78BFA',
        ],
        [
            'nombre' => 'Cuenta de Ahorros',
            'tipo' => 'banco',
            'saldo' => 0,
            'icon' => 'heroicon-o-building-library',
            'color_hex' => '#60A5FA',
        ],
    ],

    'categorias' => [
        [
            'nombre' => 'Alimentación',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-shopping-cart',
            'color_hex' => '#F6C87B',
            'orden' => 1,
        ],
        [
            'nombre' => 'Transporte',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-truck',
            'color_hex' => '#60A5FA',
            'orden' => 2,
        ],
        [
            'nombre' => 'Vivienda',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-home',
            'color_hex' => '#A78BFA',
            'orden' => 3,
        ],
        [
            'nombre' => 'Salud',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-heart',
            'color_hex' => '#FB7185',
            'orden' => 4,
        ],
        [
            'nombre' => 'Entretenimiento',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-film',
            'color_hex' => '#F472B6',
            'orden' => 5,
        ],
        [
            'nombre' => 'Educación',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-academic-cap',
            'color_hex' => '#34D399',
            'orden' => 6,
        ],
        [
            'nombre' => 'Servicios',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-tag',
            'color_hex' => '#94A3B8',
            'orden' => 7,
        ],
        [
            'nombre' => 'Otros gastos',
            'tipo' => 'gasto',
            'icon' => 'heroicon-o-ellipsis-horizontal-circle',
            'color_hex' => '#DB5656',
            'orden' => 8,
        ],
        [
            'nombre' => 'Salario',
            'tipo' => 'ingreso',
            'icon' => 'heroicon-o-briefcase',
            'color_hex' => '#8DDA90',
            'orden' => 1,
        ],
        [
            'nombre' => 'Freelance',
            'tipo' => 'ingreso',
            'icon' => 'heroicon-o-currency-dollar',
            'color_hex' => '#FAFA8B',
            'orden' => 2,
        ],
        [
            'nombre' => 'Inversiones',
            'tipo' => 'ingreso',
            'icon' => 'heroicon-o-arrow-trending-up',
            'color_hex' => '#34D399',
            'orden' => 3,
        ],
        [
            'nombre' => 'Otros ingresos',
            'tipo' => 'ingreso',
            'icon' => 'heroicon-o-gift',
            'color_hex' => '#F6C87B',
            'orden' => 4,
        ],
    ],
];
