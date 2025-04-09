@extends('layouts.master')

@section('title', 'Detail Laporan Keuangan')

@section('content')
@php
    $file = $laporan->file_laporan_keuangan;
    $fileUrl = asset('storage/' . $file);
    $extension = pathinfo($file, PATHINFO_EXTENSION);
@endphp

<section class="p-10 bg-white rounded shadow-lg">
  <div class="flex flex-row justify-between">
    <h1 class="text-2xl font-bold text-gray-700 mb-6">Laporan Keuangan</h1>
    @if(auth()->user()->role === 'direktur')
        <form action="{{ route('laporan-keuangan.update-status', $laporan->id) }}" method="POST" class="inline-block">
            @csrf
            @method('PUT')

            <select name="status_laporan" class="appearance-none border border-gray-300 rounded px-2 py-1 text-sm">
                <option value="tervalidasi" {{ $laporan->status_laporan === 'tervalidasi' ? 'selected' : '' }}>Tervalidasi</option>
                <option value="belum tervalidasi" {{ $laporan->status_laporan === 'belum tervalidasi' ? 'selected' : '' }}>Belum Tervalidasi</option>
            </select>

            <button type="submit" class="ml-2 px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-green-600">
                Simpan
            </button>
        </form>
    @else
        <p class="p-1 text-gray-700 font-medium">
            <span class="{{ $laporan->status_laporan === 'tervalidasi' ? 'p-2 rounded text-white bg-green-600' : 'p-2 rounded text-white bg-yellow-600' }}">
                {{ ucfirst($laporan->status_laporan) }}
            </span>
        </p>
    @endif
  
  </div>

    @if($extension === 'pdf')
        <iframe src="{{ $fileUrl }}" class="w-full h-[600px]" frameborder="0"></iframe>
    @elseif(in_array($extension, ['doc', 'docx']))
        <iframe src="https://docs.google.com/gview?url={{ urlencode($fileUrl) }}&embedded=true" class="w-full h-[600px]" frameborder="0"></iframe>
    @else
        <p class="text-red-500 mb-4">Format file tidak dapat dipreview.</p>
        <a href="{{ $fileUrl }}" class="text-blue-600 underline" target="_blank">Download File</a>
    @endif
</section>
@endsection
