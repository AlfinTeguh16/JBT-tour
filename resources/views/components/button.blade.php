@props([
    'variant' => 'primary', // primary | secondary | neutral | disabled | delete | edit | back
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';

    $variants = [
        // Primary merah
        'primary'   => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-400',

        // Secondary pakai biru tua gelap
        'secondary' => 'bg-blue-800 text-white hover:bg-blue-900 focus:ring-blue-600',

        // Neutral pakai abu-abu gelap
        'neutral'   => 'bg-gray-700 text-white hover:bg-gray-800 focus:ring-gray-500',

        // Delete tetap merah tapi lebih tegas
        'delete'    => 'bg-red-700 text-white hover:bg-red-800 focus:ring-red-500',

        // Edit → pakai slate (abu kebiruan gelap) biar tidak cerah
        'edit'      => 'bg-slate-600 text-white hover:bg-slate-700 focus:ring-slate-500',

        // Disabled abu-abu kusam
        'disabled'  => 'bg-gray-500 text-gray-300 cursor-not-allowed opacity-50',

        // Back → abu-abu netral
        'back'      => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
    ];

    $classes = $base.' '.$variants[$variant];
@endphp

@if($variant === 'back' && $href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'disabled' => $variant === 'disabled']) }}>
        {{ $slot }}
    </button>
@endif
