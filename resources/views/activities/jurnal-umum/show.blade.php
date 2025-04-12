@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Jurnal Umum')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Jurnal Umum</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
      <div>
          <p class="text-sm text-gray-500">Tanggal</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jumlah</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($jurnal->jumlah, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Akun Debet</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ $jurnal->akun_debet ?? '-' }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Akun Kredit</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ $jurnal->akun_kredit ?? '-' }}
          </p>
      </div>

  </div>

  <div class="mt-8 flex space-x-2">
      <x-button variant="back" href="{{ route('jurnal-umum.index') }}">Kembali</x-button>
      @if(auth()->user()->role === 'akuntan')
          <x-button variant="edit" onclick="window.location='{{ route('jurnal-umum.edit', $jurnal->id) }}'">Edit</x-button>
      @endif
  </div>
</section>
@endsection
