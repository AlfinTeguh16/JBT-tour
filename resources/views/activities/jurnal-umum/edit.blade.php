@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Edit Jurnal Umum')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
  <h1 class="font-bold text-3xl text-gray-800 mb-6">Edit Jurnal Umum</h1>

  <form action="{{ route('jurnal-umum.update', $jurnal->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal', $jurnal->tanggal->format('Y-m-d')) }}" required="true" />
      <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator" value="{{ number_format($jurnal->jumlah, 0, ',', '.') }}" required="true" />
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="akun_debet" label="Akun Debet" type="text" value="{{ old('akun_debet', $jurnal->akun_debet) }}" required="true" />
      <x-form name="akun_kredit" label="Akun Kredit" type="text" value="{{ old('akun_kredit', $jurnal->akun_kredit) }}" required="true" />
    </div>

    <x-form name="keterangan" label="Keterangan" type="textarea" value="{{ old('keterangan', $jurnal->keterangan) }}" placeholder="Opsional" />

    <div class="flex justify-end mt-4">
      <x-button variant="back" href="{{ route('jurnal-umum.index') }}" class="mx-3">Batal</x-button>
      <x-button variant="primary" type="submit">Update</x-button>
    </div>
  </form>
</section>

@include('partials.js-format-number')
@endsection
