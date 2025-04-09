@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Draft Pekerjaan')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Draft Pekerjaan</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
          <p class="text-sm text-gray-500">Kode Draft</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $draftPekerjaan->code_draft }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nama Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $draftPekerjaan->nama_pekerjaan }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Instansi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $draftPekerjaan->instansi }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">No. Telepon Instansi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $draftPekerjaan->no_instansi }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Email Instansi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $draftPekerjaan->email_instansi ?? '-' }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Tanggal Pengawasan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ $draftPekerjaan->tanggal_pengawasan ? \Carbon\Carbon::parse($draftPekerjaan->tanggal_pengawasan)->format('d F Y') : '-' }}
          </p>
      </div>
      <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Alamat Proyek</p>
          <p class="mt-1 text-lg font-medium text-gray-800 whitespace-pre-line">{{ $draftPekerjaan->alamat_proyek }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Status Pekerjaan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
            <span class="px-2 py-1 rounded 
                {{ $draftPekerjaan->status_pekerjaan == 1 ? 'bg-green-700 text-white' : 'bg-yellow-700 text-white' }}">
                {{ $draftPekerjaan->status_pekerjaan == 1 ? 'Selesai' : 'Belum Selesai' }}
        </span>
          </p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Dokumen Penawaran</p>
          @if ($draftPekerjaan->dokumen_penawaran)
              <a href="{{ Storage::url($draftPekerjaan->dokumen_penawaran) }}" target="_blank" class="text-white hover:underline px-2 py-1 rounded bg-blue-500">
                  Lihat Dokumen
              </a>
          @else
              <p class="text-gray-800">Tidak ada dokumen</p>
          @endif
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <a href="{{ route('draft-pekerjaan.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
      @if(auth()->user()->role === 'akuntan')
          <a href="{{ route('draft-pekerjaan.edit', $draftPekerjaan) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
      @endif
  </div>
</section>
@endsection
