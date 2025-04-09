@props([
    'title' => null,
    'class' => ''
])

<div {{ $attributes->merge(['class' => "bg-white shadow-lg rounded-lg p-6 space-y-4 $class"]) }}>
    @if($title)
        <h3 class="text-lg font-semibold text-gray-800">{{ $title }}</h3>
        <hr class="border-t border-gray-200">
    @endif

    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>
