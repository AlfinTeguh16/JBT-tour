@extends('layouts.master')
@section('title','Orders')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Orders</div>
      @if(in_array(auth()->user()->role, ['admin','staff']))
        <x-button :href="route('orders.create')">Tambah</x-button>
      @endif
    </div>
  </x-slot>

  <form action="{{ route('orders.index') }}" method="get" class="mb-4 flex gap-2">
    <input type="text" name="q" value="{{ $q ?? '' }}" class="rounded border-gray-300 w-full" placeholder="Cari pickup/dropoff/customer">
    <x-button type="submit" variant="secondary">Cari</x-button>
  </form>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600">
        <tr>
          <th class="text-left p-3">Requested</th>
          <th class="text-left p-3">Customer</th>
          <th class="text-left p-3">Pickup → Dropoff</th>
          <th class="text-left p-3">Status</th>
          <th class="p-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($orders as $o)
          <tr>
            <td class="p-3">{{ $o->requested_at }}</td>
            <td class="p-3">{{ $o->customer->name ?? '-' }}</td>
            <td class="p-3">{{ $o->pickup_location }} → {{ $o->dropoff_location }}</td>
            <td class="p-3">
              <x-badge>{{ ucfirst(str_replace('_',' ',$o->status)) }}</x-badge>
            </td>
            <td class="p-3 text-right">
              <x-button :href="route('orders.show',$o)" variant="link">Detail</x-button>
              @if(in_array(auth()->user()->role, ['admin','staff']))
                <x-button :href="route('orders.edit',$o)" variant="link">Edit</x-button>
                <x-form :action="route('orders.destroy',$o)" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <x-button type="submit" variant="danger">Hapus</x-button>
                </x-form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="p-3 text-gray-500">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $orders->links() }}</div>
</x-card>
@endsection
