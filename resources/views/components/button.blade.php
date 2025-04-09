@props([
    'variant' => 'primary', // primary | secondary | neutral | disabled | delete | edit
])

@php
    $base = 'inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
    $variants = [
        'primary'   => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-300',
        'secondary' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-300',
        'neutral'   => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-200',
        'delete'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
        'edit'      => 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-300',
        'disabled'  => 'bg-gray-400 text-gray-700 cursor-not-allowed opacity-50',
    ];
    $classes = $base.' '.$variants[$variant];
@endphp

<button {{ $attributes->merge(['class' => $classes, 'disabled' => $variant === 'disabled']) }}>
    {{ $slot }}
</button>
