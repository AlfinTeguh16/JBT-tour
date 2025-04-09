@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Buat Data Neraca
@endsection

@php
    use App\Enums\Bulan;
@endphp

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Buat Data Neraca</h1>

  <div>
    <form action="{{ route('data-neraca.store') }}" method="POST">
        @csrf
        <div class="flex md:flex-row md:justify-between">
          <div class="p-3 w-full">
            {{-- Dropdown bulan pakai Enum --}}
            <div class="mb-4">
              <label for="bulan" class="block font-medium text-sm text-gray-700 mb-1">Bulan <span class="text-red-500">*</span></label>
              <select name="bulan" id="bulan" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" required>
                @foreach(Bulan::cases() as $bulan)
                  <option value="{{ $bulan->value }}" @selected(old('bulan') == $bulan->value)>{{ $bulan->value }}</option>
                @endforeach
              </select>
            </div>

            <x-form name="biaya_spidi" label="Biaya SPIDI" type="text" class="only-number thousand-separator" placeholder="Contoh: 1.500.000" required="true" />

            <x-form name="biaya_listrik" label="Biaya Listrik" type="text" class="only-number thousand-separator" placeholder="Contoh: 750.000" required="true" />

            <x-form name="biaya_air_minum" label="Biaya Air Minum" type="text" class="only-number thousand-separator" placeholder="Contoh: 50.000" required="true" />
          </div>

          <div class="p-3 w-full">
            <x-form name="gaji_karyawan" label="Gaji Karyawan" type="text" class="only-number thousand-separator" placeholder="Contoh: 5.000.000" required="true" />

            <x-form name="modal_perusahaan" label="Modal Perusahaan" type="text" class="only-number thousand-separator" placeholder="Contoh: 10.000.000" required="true" />

            <x-form name="biaya_telepon" label="Biaya Telepon" type="text" class="only-number thousand-separator" placeholder="Contoh: 150.000" required="true" />
          </div>
        </div>

        <div class="flex flex-row justify-end">
          <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
          <x-button variant="primary" type="submit">Simpan</x-button>
        </div>
    </form>
  </div>
</section>

{{-- Script format angka --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll('.only-number');

    inputs.forEach(function (input) {
        // Hanya izinkan angka saat diketik
        input.addEventListener('keypress', function (e) {
            const charCode = e.which ? e.which : e.keyCode;
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        });

        // Format ribuan dengan titik
        input.addEventListener('input', function () {
            let value = this.value.replace(/\D/g, '');
            this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });

        // Bersihkan titik saat submit
        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                input.value = input.value.replace(/\./g, '');
            });
        }

        // Format awal jika ada
        let value = input.value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
});
</script>
@endsection
