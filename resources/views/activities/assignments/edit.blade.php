@extends('layouts.master')
@section('title','Edit Penugasan')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Edit Penugasan</div>
  </x-slot>

    <form action="{{ route('assignments.update',$assignment) }}" method="post">
        @csrf
        @method('PUT')
        @include('activities.assignments._form', [
        'action' => route('assignments.update',$assignment),
        'method' => 'PUT',
        ])
    </form>
</x-card>
@endsection
