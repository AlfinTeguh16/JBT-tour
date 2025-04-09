@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Edit Draft Pekerjaan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Edit Draft Pekerjaan</h1>

  <div>
    <form action="{{ route('draft-pekerjaan.update', $draftPekerjaan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex md:flex-row md:justify-between">
          <div class="p-3 w-full">
            <x-form name="nama_pekerjaan" label="Nama Proyek" type="text" 
              placeholder="Masukkan Nama Proyek" required="true"
              value="{{ old('nama_pekerjaan', $draftPekerjaan->nama_pekerjaan) }}" />

            <x-form name="instansi" label="Instansi" type="text"
              placeholder="Masukkan Nama Instansi" required="true"
              value="{{ old('instansi', $draftPekerjaan->instansi) }}" />

            <x-form name="no_instansi" label="No. Telepon Instansi" type="text"
              placeholder="Masukkan No. Telepon Instansi" required="true"
              value="{{ old('no_instansi', $draftPekerjaan->no_instansi) }}" />
            
            <x-form name="email_instansi" label="Email Instansi" type="email"
              placeholder="Masukkan Email Instansi" required="true"
              value="{{ old('email_instansi', $draftPekerjaan->email_instansi) }}" />
          </div>

          <div class="p-3 w-full">
            <x-form name="tanggal_pengawasan" label="Tanggal Pengawasan/Perencanaan" type="date"
              required="true" value="{{ old('tanggal_pengawasan', $draftPekerjaan->tanggal_pengawasan) }}" />

            <div class="mt-2">
              <label class="block text-sm font-medium text-gray-700">Dokumen Penawaran</label>
              <input type="file" name="dokumen_penawaran" class="w-full border rounded-lg px-3 py-2 text-sm" accept=".pdf,.doc,.docx,.xls">
              @if($draftPekerjaan->dokumen_penawaran)
                <p class="text-sm mt-1">Dokumen saat ini: <a href="{{ asset('storage/' . $draftPekerjaan->dokumen_penawaran) }}" target="_blank" class="text-blue-500 underline">Lihat Dokumen</a></p>
              @endif
            </div>

            <x-form name="alamat_proyek" label="Alamat Proyek" type="textarea"
              placeholder="Masukkan Alamat Proyek" required="true"
              value="{{ old('alamat_proyek', $draftPekerjaan->alamat_proyek) }}" />
          </div>
        </div>

        <div class="flex flex-row justify-end">
          <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
          <x-button variant="primary" type="submit">Update</x-button>
        </div>
    </form>
  </div>

</section>
@endsection
