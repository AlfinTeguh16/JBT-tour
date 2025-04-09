@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Transaksi Draft Pekerjaan')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Transaksi Draft Pekerjaan</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
          <p class="text-sm text-gray-500">Kode Draft</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $transaksi->draft->code_draft ?? '-' }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nama Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $transaksi->draft->nama_pekerjaan ?? '-' }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Instansi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $transaksi->draft->instansi ?? '-' }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nilai Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($transaksi->nilai_pekerjaan, 0, ',', '.') }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nilai DPP</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($transaksi->nilai_dpp, 0, ',', '.') }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nilai PPN</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($transaksi->nilai_ppn, 0, ',', '.') }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nilai PPH Final</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($transaksi->nilai_pph_final, 0, ',', '.') }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nilai Bersih Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($transaksi->nilai_bersih_pekerjaan, 0, ',', '.') }}</p>
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <a href="{{ route('transaksi-draft-pekerjaan.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
      @if(auth()->user()->role === 'akuntan')
          <a href="{{ route('transaksi-draft-pekerjaan.edit', $transaksi->id) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
      @endif
  </div>
</section>
@endsection
