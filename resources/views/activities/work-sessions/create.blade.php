@extends('layouts.master')
@section('title','Tambah Work Session')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Tambah Work Session</div>
  </x-slot>

    <form action="{{ route('work-sessions.store') }}" method="post">
        @csrf
        @include('activities.work-sessions._form', [
        'action' => route('work-sessions.store'),
        'method' => 'POST',
        ])
    </form>
</x-card>
@endsection
