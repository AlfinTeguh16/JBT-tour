@extends('layouts.master')
@section('title','Buat Penugasan')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Buat Penugasan</div>
  </x-slot>

    <form action="{{ route('assignments.store') }}" method="post">
        @csrf
        @include('activities.assignments._form', [
        'action' => route('assignments.store'),
        'method' => 'POST',
        ])
    </form>
</x-card>
@endsection
