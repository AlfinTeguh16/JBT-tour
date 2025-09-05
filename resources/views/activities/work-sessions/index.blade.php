@extends('layouts.master')
@section('title','Work Sessions')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Work Sessions</div>
      @if(in_array(auth()->user()->role,['driver','guide']))
        <x-button :href="route('work-sessions.create')">Tambah</x-button>
      @endif
    </div>
  </x-slot>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600 bg-gray-50">
        <tr>
          @if(!in_array(auth()->user()->role,['driver','guide']))
            <th class="text-left p-3">User</th>
          @endif
          <th class="text-left p-3">Customer</th>
          <th class="text-left p-3">Pickup → Dropoff</th>
          <th class="text-left p-3">Mulai</th>
          <th class="text-left p-3">Selesai</th>
          <th class="text-left p-3">Jam</th>
          <th class="p-3 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($workSessions as $ws)
          <tr class="hover:bg-gray-50">
            {{-- User (hanya terlihat untuk admin/staff) --}}
            @if(!in_array(auth()->user()->role,['driver','guide']))
              <td class="p-3">{{ $ws->user->name ?? '-' }}</td>
            @endif

            {{-- Customer --}}
            <td class="p-3">
              {{ $ws->assignment->order->customer->name ?? '-' }}
            </td>

            {{-- Pickup - Dropoff --}}
            <td class="p-3">
              @if($ws->assignment && $ws->assignment->order)
                {{ $ws->assignment->order->pickup_location ?? '-' }} →
                {{ $ws->assignment->order->dropoff_location ?? '-' }}
              @else
                -
              @endif
            </td>

            {{-- Start & End --}}
            <td class="p-3">{{ $ws->started_at }}</td>
            <td class="p-3">{{ $ws->ended_at ?? '-' }}</td>

            {{-- Total Jam --}}
            <td class="p-3">{{ number_format($ws->hours_decimal ?? 0,2) }}</td>

            {{-- Aksi --}}
            <td class="p-3 text-right space-x-1">
              <x-button onclick="window.location='{{ route('work-sessions.show',$ws) }}'" variant="secondary">Detail</x-button>

              @if (in_array(auth()->user()->role, ['staff', 'admin']) && $ws->assignment)
                <x-button onclick="window.location='{{ route('assignments.edit',$ws->assignment) }}'" variant="edit">Edit Assign</x-button>
                <x-button onclick="window.location='{{ route('work-sessions.edit',$ws) }}'" variant="edit">Edit</x-button>
                <x-form :action="route('work-sessions.destroy',$ws)" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <x-button type="submit" variant="delete" onclick="return confirm('Yakin hapus sesi ini?')">Hapus</x-button>
                </x-form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="p-3 text-center text-gray-500">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $workSessions->links() }}</div>
</x-card>
@endsection
