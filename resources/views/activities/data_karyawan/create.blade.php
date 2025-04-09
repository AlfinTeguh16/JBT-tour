@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Buat Data Karyawan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Buat Data Karyawan</h1>

  <div>
    <form action="{{ route('karyawan.store') }}" method="POST">
        @csrf
        <div class="flex md:flex-row md:justify-between">
          <div class="p-3 w-full">
            <x-form name="nama" label="Nama Karyawan" type="text" placeholder="Masukkan Nama" required="true" />

            <x-form name="no_telepon" label="No. Telepon" type="text" placeholder="Masukkan No. Telepon" required="true" />

            <x-form name="email" label="Email" type="email" required="true" />
            
            <x-form name="tanggal_lahir" label="Tanggal Lahir" type="date" required="true" />

          </div>
          <div class="p-3 w-full">
            <x-form name="tempat_lahir" label="Tempat Lahir" type="text" placeholder="Masukkan Tempat Lahir" required="true" />
            <x-form 
                name="jenis_kelamin" 
                label="Jenis Kelamin" 
                type="radio" 
                :options="[['value' => 'L', 'label' => 'Laki-laki'], ['value' => 'P', 'label' => 'Perempuan']]" 
                required="true" 
            />

            <x-form name="alamat" label="Alamat" type="textarea" placeholder="Masukkan Alamat" required="true" />
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