@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Edit Data Karyawan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800 mb-6">Edit Data Karyawan</h1>

  <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="flex md:flex-row md:justify-between">
        <div class="p-3 w-full">
          <x-form name="nama" label="Nama Karyawan" type="text" :value="$karyawan->nama" required />

          <x-form name="no_telepon" label="No. Telepon" type="text" :value="$karyawan->no_telepon" required />

          <x-form name="email" label="Email" type="email" :value="$karyawan->email" required />

          <x-form name="tanggal_lahir" label="Tanggal Lahir" type="date" :value="$karyawan->tanggal_lahir->format('Y-m-d')" required />
        </div>

        <div class="p-3 w-full">
          <x-form name="tempat_lahir" label="Tempat Lahir" type="text" :value="$karyawan->tempat_lahir" required />

          <x-form
              name="jenis_kelamin"
              label="Jenis Kelamin"
              type="radio"
              :options="[
                ['value'=>'L','label'=>'Laki-laki'],
                ['value'=>'P','label'=>'Perempuan']
              ]"
              :value="$karyawan->jenis_kelamin ? 'L':'P'"
              required
          />

          <x-form name="alamat" label="Alamat" type="textarea" :value="$karyawan->alamat" required />
          <x-form name="jabatan" label="Jabatan" type="text" :value="$karyawan->jabatan" required />
        </div>
      </div>

      <div class="flex justify-end mt-6">
        <x-button variant="neutral" onclick="window.history.back()" class="mx-3">Batal</x-button>
        <x-button variant="primary" type="submit">Update</x-button>
      </div>
  </form>
</section>
@endsection
