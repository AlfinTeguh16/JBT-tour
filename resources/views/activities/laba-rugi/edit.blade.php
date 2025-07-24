@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Edit Data Laba Rugi')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Edit Data Laba Rugi</h1>

  <form action="{{ route('laba-rugi.update', $labaRugi->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <x-form name="tanggal" label="Tanggal" type="date" required="true" value="{{ old('tanggal', $labaRugi->tanggal) }}" />

          <div>
              <label for="jenis" class="block font-medium text-sm text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
              <select name="jenis" id="jenis" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                  <option value="">Pilih Jenis</option>
                  <option value="pendapatan" @selected(old('jenis', $labaRugi->jenis) === 'pendapatan')>Pendapatan</option>
                  <option value="beban" @selected(old('jenis', $labaRugi->jenis) === 'beban')>Beban</option>
              </select>
          </div>

          <x-form name="harga_pokok_jasa" label="Harga Pokok Jasa" type="text" placeholder="Masukkan Harga Pokok Jasa" required="true" value="{{ old('harga_pokok_jasa', $labaRugi->harga_pokok_jasa) }}" />
          <x-form name="laba_kotor" label="Laba Kotor" type="text" placeholder="Masukkan Laba Kotor" required="true" value="{{ old('laba_kotor', $labaRugi->laba_kotor) }}" />
          <div id="biaya-operasi">
              <div id="biaya-penjualan-jasa">
                  <x-form name="biaya_gaji" label="Biaya Gaji" type="text" placeholder="Masukkan Biaya Gaji" required="true" value="{{ old('biaya_gaji', $labaRugi->biaya_gaji) }}" />
                  <x-form name="beban_meeting" label="Beban Meeting" type="text" placeholder="Masukkan Beban Meeting" required="true" value="{{ old('beban_meeting', $labaRugi->beban_meeting) }}" />
              </div>
              <div id="beban-administrasi">
                  <x-form name="beban_lain_lain" label="Beban Lain-Lain" type="text" placeholder="Masukkan Beban Lain-Lain" required="true" value="{{ old('beban_lain_lain', $labaRugi->beban_lain_lain) }}" />
              </div>
          </div>
          <div>
              <x-form name="jumlah_beban_operasi" label="Beban Operasi" type="text" placeholder="Masukkan Beban Operasi" required="true" value="{{ old('jumlah_beban_operasi', $labaRugi->jumlah_beban_operasi) }}" />
              <x-form name="laba_bersih_operasional" label="Laba Bersih Operasional" type="text" placeholder="Masukkan Laba Bersih Operasional" required="true" value="{{ old('laba_bersih_operasional', $labaRugi->laba_bersih_operasional) }}" />
              <x-form name="laba_bersih" label="Laba Bersih" type="text" placeholder="Masukkan Laba Bersih" required="true" value="{{ old('laba_bersih', $labaRugi->laba_bersih) }}" />
              <x-form name="jumlah" label="Jumlah (Rp)" type="text" placeholder="Contoh: 1.000.000" required="true" class="only-number thousand-separator" value="{{ old('jumlah', $labaRugi->jumlah) }}" />
          </div>
      </div>

      <div class="mt-6 flex justify-end">
          <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
          <x-button variant="primary" type="submit">Perbarui</x-button>
      </div>
  </form>
</section>

@include('partials.js-format-number')
@endsection
