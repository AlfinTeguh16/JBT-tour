@extends('layouts.master')
@section('title','Edit Kendaraan')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Kendaraan</h1>
<form action="{{ route('vehicles.update',$vehicle) }}" method="post">
    @csrf
    @include('activities.vehicles._form', [
      'action' => route('vehicles.update',$vehicle),
      'method' => 'PUT',
    ])
  </form>
@endsection
