@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Tambah Perubahan Modal')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
  <h1 class="font-bold text-3xl text-gray-800 mb-6">Tambah Perubahan Modal</h1>

  <form action="{{ route('perubahan-modal.store') }}" method="POST">
    @csrf

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal') }}" required="true" />
      <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator" placeholder="Contoh: 5000000" required="true" />
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="jenis" label="Jenis Perubahan Modal" type="select" required="true">
        <option value="">-- Pilih Jenis --</option>
        @foreach($jenisOptions as $jenis)
            <option value="{{ $jenis->value }}" {{ old('jenis') === $jenis->value ? 'selected' : '' }}>
                {{ $jenis->label() }}
            </option>
        @endforeach
    </x-form>
    
    </div>

    <x-form name="keterangan" label="Keterangan" type="textarea" value="{{ old('keterangan') }}" placeholder="Opsional" />

    <div class="flex justify-end mt-4">
      <x-button variant="neutral" onclick="window.history.back()" class="mr-2">Batal</x-button>
      <x-button variant="primary" type="submit">Simpan</x-button>
    </div>
  </form>
</section>

@include('partials.js-format-number')
@endsection
