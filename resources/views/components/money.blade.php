@props([
    'amount',
    'currency' => null,
])

{{ \App\Support\Money::format($amount, $currency) }}
