@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Arus Kas')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Arus Kas</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
      <div>
          <p class="text-sm text-gray-500">Tanggal</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($arusKas->tanggal)->translatedFormat('d F Y') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Keterangan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $arusKas->keterangan ?? '-' }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jenis</p>
          <p class="mt-1 text-lg font-medium text-gray-800 capitalize">{{ $arusKas->jenis }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Kategori</p>
          <p class="mt-1 text-lg font-medium text-gray-800 capitalize">{{ $arusKas->kategori }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jumlah</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($arusKas->jumlah, 2, ',', '.') }}
          </p>
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <a href="{{ route('arus-kas.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
      @if(auth()->user()->role === 'akuntan')
          <a href="{{ route('arus-kas.edit', $arusKas->id) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
      @endif
  </div>
</section>
@endsection
