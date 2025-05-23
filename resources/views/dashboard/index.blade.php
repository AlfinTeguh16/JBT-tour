@extends('layouts.master')

@section('title')
    CV. Cipta Arya - Dashboard
@endsection

@section('content')

@if(auth()->user()->role == 'direktur')
    <div class="w-full flex align-middle justify-center">
        <h1 class="text-4xl align-middle">Direktur Dashboard</h1>
    </div>

@elseif(auth()->user()->role == 'akuntan')
    <div class="w-full flex align-middle justify-center">
        <h1 class="text-4xl align-middle">Akuntan Dashboard</h1>
    </div>


@elseif(auth()->user()->role == 'admin')
    <div class="w-full flex align-middle justify-center">
        <h1 class="text-4xl align-middle">Admin Dashboard</h1>
    </div>

@elseif(auth()->user()->role == 'pengawas')
    <div class="w-full flex align-middle justify-center">
        <h1 class="text-4xl align-middle">Pengawas Dashboard</h1>
    </div>

@else
    <h1>Anda tidak memiliki akses ke dashboard ini</h1>
@endif


<div class="p-6">
    <div class="grid grid-cols-2 gap-4 mb-4">
        <a href="{{route('karyawan.index')}}" class="border-2 hover:shadow-md border-gray-200 hover:border-blue-400 bg-blue-100 p-4 rounded-md text-center text-gray-900 ">
            <h2 class="font-semibold text-lg">Karyawan</h2>
            <p class="text-2xl font-bold">{{ $totalKaryawan }}</p>
        </a>
        <a href="{{route('draft-pekerjaan.index')}}" class="border-2 hover:shadow-md border-gray-200 hover:border-blue-400 bg-blue-100 p-4 rounded-md text-center text-gray-900 ">
            <h2 class="font-semibold text-lg">Draft Pekerjaan</h2>
            <p class="text-2xl font-bold">{{ $totalDraftPekerjaan }}</p>
        </a>
        <a href="{{route('data-neraca.index')}}" class="border-2 hover:shadow-md border-gray-200 hover:border-blue-400 bg-blue-100 p-4 rounded-md text-center text-gray-900 ">
            <h2 class="font-semibold text-lg">Laporan Neraca</h2>
            <p class="text-2xl font-bold">{{ $totalNeraca }}</p>
        </a>
        <a href="{{route('laporan-keuangan.index')}}" class="border-2 hover:shadow-md border-gray-200 hover:border-blue-400 bg-blue-100 p-4 rounded-md text-center text-gray-900 ">
            <h2 class="font-semibold text-lg">Laporan Keuangan</h2>
            <p class="text-2xl font-bold">{{ $totalLaporanKeuangan }}</p>
        </a>
    </div>

    <div class="flex justify-center">
        <a href="{{route('transaksi-draft-pekerjaan.index')}}" class="border-2 hover:shadow-md border-gray-200 hover:border-blue-400 bg-blue-100 p-4 rounded-md text-center text-gray-900 w-1/2 ">
            <h2 class="font-semibold text-lg">Transaksi Draft Pekerjaan</h2>
            <p class="text-2xl font-bold">{{ $totalTransaksiDraft }}</p>
        </a>
    </div>
</div>

@endsection
