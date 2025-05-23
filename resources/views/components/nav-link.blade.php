@props(['href', 'active'])

@php
$classes = [
    'flex items-center px-4 py-2 rounded-lg transition',
    $active ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600'
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => implode(' ', $classes)]) }}>
    {{ $slot }}
</a>
