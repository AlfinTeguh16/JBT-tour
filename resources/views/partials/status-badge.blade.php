@php
  $status = ($status ?? '') ?: '-';
  $map = [
    'pending'      => 'bg-yellow-100 text-yellow-700',
    'assigned'     => 'bg-blue-100 text-blue-700',
    'in_progress'  => 'bg-indigo-100 text-indigo-700',
    'completed'    => 'bg-emerald-100 text-emerald-700',
    'cancelled'    => 'bg-red-100 text-red-700',
    '-'            => 'bg-gray-100 text-gray-700',
  ];
  $cls = $map[$status] ?? 'bg-gray-100 text-gray-700';
@endphp

<x-badge class="{{ $cls }}">
  {{ is_string($status) ? ucfirst(str_replace('_',' ',$status)) : $status }}
</x-badge>
