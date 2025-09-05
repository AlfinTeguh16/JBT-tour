@extends('layouts.master')
@section('title','Orders')

@section('content')
<x-card>
  <div name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Orders</div>
      @if(in_array(auth()->user()->role, ['admin','staff']))
        <x-button onclick="window.location='{{ route('orders.create') }}'">Tambah</x-button>
      @endif
    </div>
  </div>

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
                @php
                    $statusColors = [
                        'pending'     => 'bg-yellow-100 text-yellow-800',
                        'assigned'    => 'bg-blue-100 text-blue-800',
                        'in_progress' => 'bg-indigo-100 text-indigo-800',
                        'completed'   => 'bg-green-100 text-green-800',
                        'cancelled'   => 'bg-red-100 text-red-800',
                    ];
                    $cls = $statusColors[$o->status] ?? 'bg-gray-100 text-gray-800';
                @endphp

                <span class="px-2 py-1 text-xs font-medium rounded {{ $cls }}">
                    {{ ucfirst(str_replace('_',' ',$o->status)) }}
                </span>
            </td>
            <td class="p-3 text-right">
              <x-button onclick="window.location='{{ route('orders.show',$o) }}'" variant="secondary">Detail</x-button>
              @if(in_array(auth()->user()->role, ['admin','staff']))
                <x-button onclick="window.location='{{ route('orders.edit',$o) }}'" variant="edit">Edit</x-button>
                <x-form :action="route('orders.destroy',$o)" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <x-button type="submit" variant="delete">Hapus</x-button>
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
