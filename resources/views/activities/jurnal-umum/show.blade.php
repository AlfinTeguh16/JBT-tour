@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Jurnal Umum')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
  <h1 class="font-bold text-3xl text-gray-800 mb-6">Detail Jurnal Umum</h1>

  <div class="grid md:grid-cols-2 gap-4">
      <div>
          <p class="text-sm text-gray-500">Jenis Transaksi</p>
          <p class="mt-1 text-lg font-medium text-gray-800 capitalize">
              {{ str_replace('-', ' ', $jurnal->transaksi) }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Tanggal</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}
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

      <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Keterangan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ $jurnal->keterangan ?? '-' }}
          </p>
      </div>
  </div>

  <div class="mt-8 flex justify-end space-x-2">
      <x-button variant="neutral" onclick="window.location='{{ route('jurnal-umum.index') }}'">Kembali</x-button>
      @if(auth()->user()->role === 'akuntan')
          <x-button variant="primary" onclick="window.location='{{ route('jurnal-umum.edit', $jurnal->id) }}'">Edit</x-button>
      @endif
  </div>
</section>
@endsection
