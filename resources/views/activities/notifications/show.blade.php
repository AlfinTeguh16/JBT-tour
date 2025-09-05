@extends('layouts.master')
@section('title','Detail Notification')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <div class="text-xl font-semibold">{{ $notification->title }}</div>
      <x-badge>{{ $notification->is_read ? 'Read' : 'New' }}</x-badge>
    </div>
  </x-slot>

  @if($notification->body)
    <div class="text-gray-800 whitespace-pre-line">{{ $notification->body }}</div>
  @endif

  <div class="text-sm text-gray-500 mt-4">{{ $notification->created_at->format('d M Y H:i') }}</div>
</x-card>
@endsection
