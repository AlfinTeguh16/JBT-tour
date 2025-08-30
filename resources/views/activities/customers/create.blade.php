@extends('layouts.master')
@section('title','Tambah Customer')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Customer</h1>
<form action="{{ route('customers.store') }}" method="post" class="bg-white p-5 rounded border">
  @include('activities.customers._form')
</form>
@endsection
