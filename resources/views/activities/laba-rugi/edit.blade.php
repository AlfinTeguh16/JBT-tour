@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Edit Data Laba Rugi')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Edit Data Laba Rugi</h1>

  <form action="{{ route('laba-rugi.update', $labaRugi->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <x-form name="tanggal" label="Tanggal" type="date" required="true" value="{{ old('tanggal', $labaRugi->tanggal->format('Y-m-d')) }}" />

      <div>
        <label for="jenis" class="block font-medium text-sm text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
        <select name="jenis" id="jenis" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-primary focus:border-primary">
          <option value="">-- Pilih Jenis --</option>
          <option value="pendapatan" @selected(old('jenis', $labaRugi->jenis) === 'pendapatan')>Pendapatan</option>
          <option value="beban" @selected(old('jenis', $labaRugi->jenis) === 'beban')>Beban</option>
        </select>
      </div>

      <x-form name="keterangan" label="Keterangan" type="text" placeholder="Masukkan keterangan" required="true" value="{{ old('keterangan', $labaRugi->keterangan) }}" />

      <x-form name="jumlah" label="Jumlah (Rp)" type="text" placeholder="Contoh: 1.000.000" required="true" class="only-number thousand-separator" value="{{ old('jumlah', number_format($labaRugi->jumlah, 0, ',', '.')) }}" />
    </div>

    <div class="mt-6 flex justify-end">
      <x-button variant="neutral" type="button" onclick="window.history.back()" class="mx-3">Batal</x-button>
      <x-button variant="primary" type="submit">Update</x-button>
    </div>
  </form>
</section>

@include('partials.js-format-number')
@endsection
