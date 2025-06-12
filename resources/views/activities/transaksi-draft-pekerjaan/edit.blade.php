@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Edit Transaksi Draft Pekerjaan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Edit Transaksi Draft Pekerjaan</h1>

  <div>
    <form action="{{ route('transaksi-draft-pekerjaan.update', $transaksi->id) }}" method="POST">
      @csrf
      @method('PUT')
        <input type="hidden" name="draft_pekerjaan_id" value="{{ $transaksi->draft_pekerjaan_id }}">

      <div class="flex md:flex-row md:justify-between">
        <div class="p-3 w-full">
          <x-form name="nilai_pekerjaan" label="Nilai Pekerjaan" type="number"
            placeholder="Masukkan Nilai Pekerjaan" required="true"
            value="{{ old('nilai_pekerjaan', $transaksi->nilai_pekerjaan) }}" />

          <x-form name="nilai_dpp" label="Nilai DPP" type="number"
            placeholder="Masukkan Nilai DPP" required="true"
            value="{{ old('nilai_dpp', $transaksi->nilai_dpp) }}" />

          <x-form name="nilai_ppn" label="Nilai PPN" type="number"
            placeholder="Masukkan Nilai PPN" required="true"
            value="{{ old('nilai_ppn', $transaksi->nilai_ppn) }}" />
        </div>

        <div class="p-3 w-full">
          <x-form name="nilai_pph_final" label="Nilai PPH Final" type="number"
            placeholder="Masukkan Nilai PPH Final" required="true"
            value="{{ old('nilai_pph_final', $transaksi->nilai_pph_final) }}" />

          <x-form name="nilai_bersih_pekerjaan" label="Nilai Bersih Pekerjaan" type="number"
            placeholder="Masukkan Nilai Bersih Pekerjaan" required="true"
            value="{{ old('nilai_bersih_pekerjaan', $transaksi->nilai_bersih_pekerjaan) }}" />

          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Informasi Draft Pekerjaan</label>
            <div class="mt-1 bg-gray-100 rounded-md p-3 text-sm text-gray-800 space-y-1">
              <p><strong>Code Draft:</strong> {{ $transaksi->draft->code_draft ?? '-' }}</p>
              <p><strong>Nama Pekerjaan:</strong> {{ $transaksi->draft->nama_pekerjaan ?? '-' }}</p>
              <p><strong>Instansi:</strong> {{ $transaksi->draft->instansi ?? '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex flex-row justify-end mt-6">
        <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
        <x-button variant="primary" type="submit">Update</x-button>
      </div>
    </form>
  </div>
</section>
@endsection
