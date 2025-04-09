@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Data Neraca')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Data Neraca</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
      <div>
          <p class="text-sm text-gray-500">Bulan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $neraca->bulan }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Biaya SPIDI</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->biaya_spidi, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Biaya Listrik</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->biaya_listrik, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Biaya Air Minum</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->biaya_air_minum, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Gaji Karyawan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->gaji_karyawan, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Modal Perusahaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->modal_perusahaan, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Biaya Telepon</p>
          <p class="mt-1 text-lg font-medium text-gray-800">Rp {{ number_format($neraca->biaya_telepon, 0, ',', '.') }}</p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Status Data Karyawan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              <span class="px-2 py-1 rounded {{ $neraca->data_karyawan ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                  {{ $neraca->data_karyawan ? 'Aktif' : 'Tidak Aktif' }}
              </span>
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Status Draft Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              <span class="px-2 py-1 rounded {{ $neraca->draft_pekerjaan ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                  {{ $neraca->draft_pekerjaan ? 'Aktif' : 'Tidak Aktif' }}
              </span>
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Status Transaksi Draft Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              <span class="px-2 py-1 rounded {{ $neraca->transaksi_draft_pekerjaan ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                  {{ $neraca->transaksi_draft_pekerjaan ? 'Aktif' : 'Tidak Aktif' }}
              </span>
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Status Transaksi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              <span class="px-2 py-1 rounded {{ $neraca->status_transaksi ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                  {{ $neraca->status_transaksi ? 'Aktif' : 'Tidak Aktif' }}
              </span>
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Status Draft</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              <span class="px-2 py-1 rounded {{ $neraca->status_draft_pekerjaan ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' }}">
                  {{ $neraca->status_draft_pekerjaan ? 'Aktif' : 'Tidak Aktif' }}
              </span>
          </p>
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <a href="{{ route('data-neraca.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
      @if(auth()->user()->role === 'akuntan')
          <a href="{{ route('data-neraca.edit', $neraca->id) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
      @endif
  </div>
</section>
@endsection
