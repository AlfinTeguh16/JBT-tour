@props(['href','active'])

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'flex items-center px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition']) }}
   @class(['bg-blue-50 text-blue-600' => $active])
>
  {{ $slot }}
</a>
