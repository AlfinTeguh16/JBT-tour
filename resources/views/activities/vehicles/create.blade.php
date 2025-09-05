@extends('layouts.master')
@section('title','Tambah Kendaraan')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Tambah Kendaraan</div>
  </x-slot>

  <form action="{{ route('vehicles.store') }}" method="post">
    @csrf
    @include('activities.vehicles._form', [
      'action' => route('vehicles.store'),
      'method' => 'POST',
    ])
  </form>
</x-card>
@endsection
