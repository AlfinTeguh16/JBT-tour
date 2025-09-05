@extends('layouts.master')
@section('title','Edit Customer')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Customer</h1>
<form action="{{ route('customers.update',$customer) }}" method="post" class="bg-white p-5 rounded border">
  @method('PUT')
  @include('activities.customers._form')
</form>
@endsection
