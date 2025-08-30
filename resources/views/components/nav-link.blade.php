@props(['href', 'active'])

@php
$classes = [
    'flex items-center px-4 py-2 rounded-lg transition',
    $active ? 'bg-gray-100 text-gray-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-600'
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => implode(' ', $classes)]) }}>
    {{ $slot }}
</a>
