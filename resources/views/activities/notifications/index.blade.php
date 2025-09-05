@extends('layouts.master')
@section('title','Notifications')

@section('content')
<x-card>
  <x-slot name="header"><div class="text-xl font-semibold">Notifications</div></x-slot>

  <div class="divide-y">
    @foreach($notifications as $n)
        <x-card class="mb-3">
            <div class="flex justify-between items-center">
            <div>
                <div class="font-semibold">{{ $n->title }}</div>
                <p class="text-sm text-gray-600">{{ $n->body }}</p>
                @if($n->order)
                <p class="text-xs text-gray-500">
                    Pickup: {{ $n->order->pickup_location }} → Dropoff: {{ $n->order->dropoff_location }}
                </p>
                @endif
            </div>
            <div class="flex gap-2">
                @if($n->status === 'pending')
                <form action="{{route('notifications.approve', $n)}}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <x-button type="submit" variant="primary">Approve</x-button>
                </form>
                <form action="{{route('notifications.decline', $n)}}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <x-button type="submit" variant="delete">Decline</x-button>
                </form>
                @else
                <x-badge class="{{ $n->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($n->status) }}
                </x-badge>
                @endif
            </div>
            </div>
        </x-card>
        @endforeach
        @if ($notifications->isEmpty())
            <div class="text-center py-4">
                <p class="text-gray-500">Belum ada notifikasi.</p>
            </div>
        @endif

  </div>

  <div class="mt-4">{{ $notifications->links() }}</div>
</x-card>
@endsection
