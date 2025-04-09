@extends('layouts.master')

@section('title')
    CV. Cipta Arya - Dashboard
@endsection

@section('content')

@if(auth()->user()->role == 'direktur')
    <h1>Direktur Dashboard</h1>

@elseif(auth()->user()->role == 'akuntan')
    <h1>Akuntan Dashboard</h1>

@elseif(auth()->user()->role == 'admin')
    <h1>Admin Dashboard</h1>

@elseif(auth()->user()->role == 'pengawas')
    <h1>Pengawas Dashboard</h1>

@else
    <h1>Anda tidak memiliki akses ke dashboard ini</h1>
@endif

@endsection
