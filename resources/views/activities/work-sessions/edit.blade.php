@extends('layouts.master')
@section('title','Edit Work Session')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Edit Work Session</div>
  </x-slot>

    <form action="{{ route('work-sessions.update',$workSession) }}" method="post">
        @csrf
        @include('activities.work-sessions._form', [
        'action' => route('work-sessions.update',$workSession),
        'method' => 'POST',
        ])
    </form>
</x-card>
@endsection
