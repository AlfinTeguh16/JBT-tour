@extends('layouts.master')

@section('title')
  CV. Cipta Arya - Detail Data Karyawan
@endsection

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Data Karyawan</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
          <p class="text-sm text-gray-500">ID Karyawan</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->id_karyawan }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Nama</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->nama }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Email</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->email }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">No. Telepon</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->no_telepon }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Tanggal Lahir</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d F Y') }}</p>
      </div>
      <div>
          <p class="text-sm text-gray-500">Tempat Lahir</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->tempat_lahir }}</p>
      </div>
      <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Jenis Kelamin</p>
          <p class="mt-1 text-lg font-medium text-gray-800">{{ $karyawan->jenis_kelamin ? 'Laki‑Laki' : 'Perempuan' }}</p>
      </div>
      <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Alamat</p>
          <p class="mt-1 text-lg font-medium text-gray-800 whitespace-pre-line">{{ $karyawan->alamat }}</p>
      </div>
  </div>

  <div class="mt-8 flex space-x-2">
      <a href="{{ route('karyawan.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Kembali</a>
      @if(auth()->user()->role === 'akuntan')
          <a href="{{ route('karyawan.edit', $karyawan) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
      @endif
  </div>
</section>
@endsection
