@extends('layouts.master')
@section('title', 'Detail Kendaraan')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2 class="text-xl font-semibold">Detail Kendaraan</h2>
      <div class="flex gap-2">
        <x-button :href="route('vehicles.edit', $vehicle)" variant="primary">Edit</x-button>
        <x-form :action="route('vehicles.destroy',$vehicle)" method="POST" class="inline">
            @csrf @method('DELETE')
            <x-button type="submit" variant="delete">Hapus</x-button>
        </x-form>
      </div>
    </div>
  </x-slot>

  <div class="space-y-4">
    <div>
      <span class="block text-sm text-gray-500">Nomor Polisi</span>
      <p class="font-medium text-gray-900">{{ $vehicle->plate_no }}</p>
    </div>

    <div>
      <span class="block text-sm text-gray-500">Merek</span>
      <p class="font-medium text-gray-900">{{ $vehicle->brand ?? '-' }}</p>
    </div>

    <div>
      <span class="block text-sm text-gray-500">Model</span>
      <p class="font-medium text-gray-900">{{ $vehicle->model ?? '-' }}</p>
    </div>

    <div>
      <span class="block text-sm text-gray-500">Kapasitas</span>
      <p class="font-medium text-gray-900">{{ $vehicle->capacity ?? '-' }}</p>
    </div>

    <div>
      <span class="block text-sm text-gray-500">Status</span>
      @php
        $statusColors = [
          'available'   => 'bg-green-100 text-green-800',
          'in_use'      => 'bg-blue-100 text-blue-800',
          'maintenance' => 'bg-yellow-100 text-yellow-800',
        ];
        $cls = $statusColors[$vehicle->status] ?? 'bg-gray-100 text-gray-800';
      @endphp
      <x-badge class="{{ $cls }}">{{ ucfirst(str_replace('_',' ',$vehicle->status)) }}</x-badge>
    </div>

    
  </div>
</x-card>

{{-- Riwayat Penugasan --}}
<x-card class="mt-6">
  <x-slot name="header">
    <h2 class="text-lg font-semibold">Riwayat Penugasan</h2>
  </x-slot>

  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left">Order</th>
          <th class="px-4 py-2 text-left">Driver</th>
          <th class="px-4 py-2 text-left">Guide</th>
          <th class="px-4 py-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($vehicle->assignments as $a)
          <tr>
            <td class="px-4 py-2">
              <a href="{{ route('orders.show', $a->order_id) }}" class="text-blue-600 hover:underline">
                #{{ $a->order_id }}
              </a>
            </td>
            <td class="px-4 py-2">{{ $a->driver?->name ?? '-' }}</td>
            <td class="px-4 py-2">{{ $a->guide?->name ?? '-' }}</td>
            <td class="px-4 py-2">
              <x-badge class="text-gray-500">{{ $a->status_message }}</x-badge>
            </td>
          </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-2 text-gray-500">Belum ada penugasan.</td>
            </tr>

        @endforelse
      </tbody>
    </table>
  </div>
</x-card>
@endsection
