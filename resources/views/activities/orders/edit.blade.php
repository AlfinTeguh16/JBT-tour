@extends('layouts.master')
@section('title','Edit Order')

@section('content')
<x-card>
  <x-slot name="header">
    <div class="text-xl font-semibold">Edit Order</div>
  </x-slot>
  @include('activities.orders._form', [
    'action' => route('orders.update',$order),
    'method' => 'PUT',
  ])
</x-card>
@endsection
