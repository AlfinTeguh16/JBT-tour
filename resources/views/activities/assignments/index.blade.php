@extends('layouts.master')
@section('title','Assignments')

@section('content')
<x-card>
  <div name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Assignments</div>
      @if(in_array(auth()->user()->role,['admin','staff']))
        <x-button onclick="window.location='{{ route('assignments.create') }}'">Buat Penugasan</x-button>
      @endif
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600">
        <tr>
          <th class="text-left p-3">Start</th>
          <th class="text-left p-3">Customer</th>
          <th class="text-left p-3">Driver</th>
          <th class="text-left p-3">Guide</th>
          <th class="text-left p-3">Vehicle</th>
          <th class="text-left p-3">Status</th>
          <th class="p-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($assignments as $a)
          <tr>
            <td class="p-3">{{ $a->scheduled_start ?? '-' }}</td>
            <td class="p-3">{{ $a->order->customer->name ?? '-' }}</td>
            <td class="p-3">{{ $a->driver->name ?? '-' }}</td>
            <td class="p-3">{{ $a->guide->name ?? '-' }}</td>
            <td class="p-3">{{ $a->vehicle->plate_no ?? '-' }}</td>
            <td class="p-3">{{ ucfirst(str_replace('_',' ',$a->status)) }}</td>
            <td class="p-3 text-right">
              <x-button onclick="window.location='{{ route('assignments.show',$a) }}'" variant="secondary">Detail</x-button>
              @if(in_array(auth()->user()->role,['admin','staff']))
                <x-button onclick="window.location='{{ route('assignments.edit',$a) }}'" variant="edit">Edit</x-button>
                <x-form :action="route('assignments.destroy',$a)" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <x-button type="submit" variant="delete">Hapus</x-button>
                </x-form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="p-3 text-gray-500">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $assignments->links() }}</div>
</x-card>
@endsection
