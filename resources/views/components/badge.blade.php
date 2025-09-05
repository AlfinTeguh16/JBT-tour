@props([
    'variant' => 'success', // success | warning | failed
    'position' => 'top-right', // top-right | top-left | bottom-right | bottom-left
])

@php
    $base = ' inline-block w-fit p-2 text-xs font-semibold rounded-lg shadow';
    $variants = [
        'success' => 'bg-green-800 text-white',
        'warning' => 'bg-yellow-800 text-gray-800',
        'failed'  => 'bg-red-800 text-white',
    ];
    $positions = [
        'top-right'    => 'top-2 right-2',
        'top-left'     => 'top-2 left-2',
        'bottom-right' => 'bottom-2 right-2',
        'bottom-left'  => 'bottom-2 left-2',
    ];
    $classes = $base.' '.$variants[$variant].' '.$positions[$position];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
