@extends('layouts.master')
@section('title','Tambah Order')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Tambah Order</div>
  </x-slot>
  @include('activities.orders._form', [
    'action' => route('orders.store'),
    'method' => 'POST',
  ])
</x-card>
@endsection
