@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Buat Draft Pekerjaan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Buat Draft Pekerjaan</h1>

  <div>
    <form action="{{ route('draft-pekerjaan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex md:flex-row md:justify-between">
          <div class="p-3 w-full">
            <x-form name="nama_pekerjaan" label="Nama Proyek" type="text" placeholder="Masukkan Nama Proyek" required="true" />

            <x-form name="instansi" label="Instansi" type="text" placeholder="Masukkan Nama Instansi" required="true" />

            <x-form name="no_instansi" label="No. Telepon Instansi" type="text" placeholder="Masukkan No. Telepon Instansi" required="true" />
            
            <x-form name="email_instansi" label="Email Instansi" type="email" placeholder="Masukkan Email Instansi" required="true" />
          </div>

          <div class="p-3 w-full">
            <x-form name="tanggal_pengawasan" label="Tanggal Pengawasan/Perencanaan" type="date" required="true" />
            
            <x-form name="dokumen_penawaran" label="Dokumen Penawaran" type="file" placeholder="Masukkan Dokumen Penawaran" required="true" />

            <x-form name="alamat_proyek" label="Alamat Proyek" type="textarea" placeholder="Masukkan Alamat Proyek" required="true" />
          </div>
        </div>
        <div class="flex flex-row justify-end">
          <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
          <x-button variant="primary" type="submit">Simpan</x-button>
        </div>
    </form>
</div>

</section>
@endsection