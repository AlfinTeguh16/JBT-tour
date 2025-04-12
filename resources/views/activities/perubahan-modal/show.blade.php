@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Perubahan Modal')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Perubahan Modal</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
      <div>
          <p class="text-sm text-gray-500">Tanggal</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ \Carbon\Carbon::parse($modal->tanggal)->translatedFormat('d F Y') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jumlah</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($modal->jumlah, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jenis Perubahan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ \App\Enums\PerubahanModalJenisEnum::from($modal->jenis)->label() }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Keterangan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ $modal->keterangan ?? '-' }}
          </p>
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <x-button variant="back" href="{{ route('perubahan-modal.index') }}">Kembali</x-button>
      @if(auth()->user()->role === 'akuntan')
          <x-button variant="edit" onclick="window.location='{{ route('perubahan-modal.edit', $modal->id) }}'">Edit</x-button>
      @endif
  </div>
</section>
@endsection
