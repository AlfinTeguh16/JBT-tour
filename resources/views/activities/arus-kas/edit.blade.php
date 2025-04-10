@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Edit Arus Kas
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Edit Arus Kas</h1>

  <div>
    <form action="{{ route('arus-kas.update', $arusKas->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="flex md:flex-row md:justify-between">
          <div class="p-3 w-full">
            <x-form name="tanggal" label="Tanggal Transaksi" type="date" required="true" value="{{ old('tanggal', $arusKas->tanggal->format('Y-m-d')) }}" />

            <x-form name="keterangan" label="Keterangan" type="text" placeholder="Contoh: Pembelian peralatan" required="false" value="{{ old('keterangan', $arusKas->keterangan) }}" />

            <div class="mb-4">
              <label for="jenis" class="block font-medium text-sm text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
              <select name="jenis" id="jenis" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                <option value="masuk" @selected(old('jenis', $arusKas->jenis) == 'masuk')>Masuk</option>
                <option value="keluar" @selected(old('jenis', $arusKas->jenis) == 'keluar')>Keluar</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="kategori" class="block font-medium text-sm text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
              <select name="kategori" id="kategori" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                <option value="operasional" @selected(old('kategori', $arusKas->kategori) == 'operasional')>Operasional</option>
                <option value="investasi" @selected(old('kategori', $arusKas->kategori) == 'investasi')>Investasi</option>
                <option value="pendanaan" @selected(old('kategori', $arusKas->kategori) == 'pendanaan')>Pendanaan</option>
              </select>
            </div>
          </div>

          <div class="p-3 w-full">
            <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator"
              placeholder="Contoh: 1.000.000" required="true"
              value="{{ old('jumlah', number_format($arusKas->jumlah, 0, '', '.')) }}" />
          </div>
        </div>

        <div class="flex flex-row justify-end">
          <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
          <x-button variant="primary" type="submit">Update</x-button>
        </div>
    </form>
  </div>
</section>

{{-- Script format angka --}}
<script>
  document.addEventListener("DOMContentLoaded", function () {
      const inputs = document.querySelectorAll('.only-number');
  
      inputs.forEach(function (input) {
          // Izinkan hanya angka diketik
          input.addEventListener('keypress', function (e) {
              const charCode = e.which || e.keyCode;
              if (charCode < 48 || charCode > 57) {
                  e.preventDefault();
              }
          });
  
          // Format ribuan dengan titik
          input.addEventListener('input', function () {
              let value = this.value.replace(/\D/g, ''); // hapus semua non-digit
              this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          });
  
          // Hapus titik saat submit
          const form = input.closest('form');
          if (form) {
              form.addEventListener('submit', function () {
                  input.value = input.value.replace(/\./g, ''); // Hapus semua titik
              });
          }
  
          // Format awal
          let value = input.value.replace(/\D/g, '');
          input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      });
  });
</script>
  
@endsection
