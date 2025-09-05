@extends('layouts.master')
@section('title','Detail Penugasan')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Detail Penugasan</div>
  </x-slot>

  <div class="grid gap-3 md:grid-cols-3">
    <div><span class="text-sm text-gray-500">Customer</span><div class="font-medium">{{ $assignment->order->customer->name ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">Driver</span><div class="font-medium">{{ $assignment->driver->name ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">Guide</span><div class="font-medium">{{ $assignment->guide->name ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">Vehicle</span><div class="font-medium">{{ $assignment->vehicle->plate_no ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">Start</span><div class="font-medium">{{ $assignment->scheduled_start ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">End</span><div class="font-medium">{{ $assignment->scheduled_end ?? '-' }}</div></div>
    <div class="md:col-span-3">
      <span class="text-sm text-gray-500">Status</span>
       <div class="mt-1">
        <x-badge>
            {{ ucwords(str_replace('_', ' ', $assignment->status)) }}
        </x-badge>
        </div>
    </div>
  </div>
</x-card>

<x-card class="mt-6">
  <x-slot name="header"><div class="text-lg font-semibold">Work Sessions</div></x-slot>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="border-b text-gray-600">
        <tr>
          <th class="text-left p-3">User</th>
          <th class="text-left p-3">Mulai</th>
          <th class="text-left p-3">Selesai</th>
          <th class="text-left p-3">Jam</th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @forelse($assignment->workSessions as $ws)
          <tr>
            <td class="p-3">{{ $ws->user->name ?? '-' }}</td>
            <td class="p-3">{{ $ws->started_at }}</td>
            <td class="p-3">{{ $ws->ended_at ?? '-' }}</td>
            <td class="p-3">{{ number_format($ws->hours_decimal ?? 0,2) }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="p-3 text-gray-500">Belum ada sesi.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(in_array(Auth::id(), [$assignment->driver_id, $assignment->guide_id]))
    @php
        $activeSession = $assignment->workSessions()
            ->where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->first();
    @endphp

    @if(!$activeSession)
        <x-form :action="route('work-sessions.start', $assignment)" method="POST">
        @csrf
        <x-button type="submit" variant="primary" onclick="startTracking({{ $assignment->id }})">Start</x-button>
        </x-form>
    @else
        <x-form :action="route('work-sessions.stop', $activeSession)" method="POST">
        @csrf
        <x-button type="submit" variant="delete" onclick="stopTracking()">Stop</x-button>
        </x-form>
    @endif
    @endif

</x-card>
@endsection
