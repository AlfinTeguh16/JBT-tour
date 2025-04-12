@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Edit Perubahan Modal')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl w-full">
  <h1 class="font-bold text-3xl text-gray-800 mb-6">Edit Perubahan Modal</h1>

  <form action="{{ route('perubahan-modal.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="tanggal" label="Tanggal" type="date" value="{{ old('tanggal', $data->tanggal->format('Y-m-d')) }}" required="true" />
      <x-form name="jumlah" label="Jumlah" type="text" class="only-number thousand-separator" value="{{ number_format($data->jumlah, 0, ',', '.') }}" required="true" />
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
      <x-form name="jenis" label="Jenis Perubahan Modal" type="select" required="true">
        <option value="">-- Pilih Jenis --</option>
        @foreach($jenisOptions as $jenis)
            <option 
                value="{{ $jenis->value }}" 
                @selected(old('jenis', $data->jenis) === $jenis->value)>
                {{ $jenis->label() }}
            </option>
        @endforeach
    </x-form>
    
    </div>

    <x-form name="keterangan" label="Keterangan" type="textarea" value="{{ old('keterangan', $data->keterangan) }}" placeholder="Opsional" />

    <div class="flex justify-end mt-4">
      <x-button variant="back" href="{{ route('perubahan-modal.index') }}" class="mx-3">Batal</x-button>
      <x-button variant="primary" type="submit">Update</x-button>
    </div>
  </form>
</section>

@include('partials.js-format-number')
@endsection
