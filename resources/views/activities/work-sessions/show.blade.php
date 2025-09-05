@extends('layouts.master')
@section('title','Detail Work Session')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Detail Work Session</div>
  </x-slot>

  <div class="grid gap-3 md:grid-cols-2">
    {{-- User --}}
    <div>
      <span class="text-sm text-gray-500">User</span>
      <div class="font-medium">{{ $workSession->user->name ?? '-' }}</div>
    </div>

    

    {{-- Customer --}}
    <div>
      <span class="text-sm text-gray-500">Customer</span>
      <div class="font-medium">
        {{ $workSession->assignment->order->customer->name ?? '-' }}
      </div>
    </div>

    {{-- Order detail --}}
    <div>
      <span class="text-sm text-gray-500">Order</span>
      <div class="font-medium">
        @if($workSession->assignment && $workSession->assignment->order)
          Pickup: {{ $workSession->assignment->order->pickup_location ?? '-' }} <br>
          Dropoff: {{ $workSession->assignment->order->dropoff_location ?? '-' }}
        @else
          -
        @endif
      </div>
    </div>

    {{-- Mulai --}}
    <div>
      <span class="text-sm text-gray-500">Mulai</span>
      <div class="font-medium">{{ $workSession->started_at }}</div>
    </div>

    {{-- Selesai --}}
    <div>
      <span class="text-sm text-gray-500">Selesai</span>
      <div class="font-medium">{{ $workSession->ended_at ?? '-' }}</div>
    </div>

    {{-- Total Jam --}}
    <div class="md:col-span-2">
      <span class="text-sm text-gray-500">Durasi Jam Kerja</span>
      <div class="font-medium">
        {{ number_format($workSession->hours_decimal ?? 0,2) }} jam
      </div>
    </div>
  </div>
</x-card>
@endsection
