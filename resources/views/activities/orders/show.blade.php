@extends('layouts.master')
@section('title','Detail Order')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">Detail Order</div>
      @if(in_array(auth()->user()->role, ['admin','staff']))
        <x-button :href="route('assignments.create', ['order_id'=>$order->id])">Tugaskan</x-button>
      @endif
    </div>
  </x-slot>

  <div class="grid gap-3 md:grid-cols-2">
    <div><span class="text-sm text-gray-500">Customer</span><div class="font-medium">{{ $order->customer->name ?? '-' }}</div></div>
    <div><span class="text-sm text-gray-500">Requested</span><div class="font-medium">{{ $order->requested_at }}</div></div>
    <div><span class="text-sm text-gray-500">Service Date</span><div class="font-medium">{{ $order->service_date ?? '-' }}</div></div>
    <div class="md:col-span-2"><span class="text-sm text-gray-500">Rute</span><div class="font-medium">{{ $order->pickup_location }} → {{ $order->dropoff_location }}</div></div>
    <div class="md:col-span-2"><span class="text-sm text-gray-500">Catatan</span><div class="font-medium">{{ $order->notes ?? '-' }}</div></div>
    <div class="md:col-span-2">
      <span class="text-sm text-gray-500">Status</span>
      <div class="mt-1">{{ ucfirst(str_replace('_',' ',$order->status)) }}</div>
    </div>
  </div>
</x-card>

<x-card class="mt-6">
  <x-slot name="header">
    <div class="text-lg font-semibold">Penugasan</div>
  </x-slot>

  @if($order->assignment)
    <div class="grid gap-2 md:grid-cols-3">
      <div><span class="text-sm text-gray-500">Driver</span><div class="font-medium">{{ $order->assignment->driver->name ?? '-' }}</div></div>
      <div><span class="text-sm text-gray-500">Guide</span><div class="font-medium">{{ $order->assignment->guide->name ?? '-' }}</div></div>
      <div><span class="text-sm text-gray-500">Vehicle</span><div class="font-medium">{{ $order->assignment->vehicle->plate_no ?? '-' }}</div></div>
      <div><span class="text-sm text-gray-500">Start</span><div class="font-medium">{{ $order->assignment->scheduled_start ?? '-' }}</div></div>
      <div><span class="text-sm text-gray-500">End</span><div class="font-medium">{{ $order->assignment->scheduled_end ?? '-' }}</div></div>
      <div><span class="text-sm text-gray-500">Status</span>
        <div class="mt-1"><x-badge>{{ ucwords(str_replace('_', ' ', $order->assignment->status)) }}</x-badge></div>
      </div>
    </div>
    <div class="mt-4">
      <x-button onclick="window.location='{{ route('assignments.show',$order->assignment) }}'" variant="secondary">Lihat Penugasan</x-button>
    </div>
  @else
    <div class="text-gray-500">Belum ada penugasan.</div>
  @endif
</x-card>
@endsection
