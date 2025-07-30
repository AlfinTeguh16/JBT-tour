@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Detail Laba Rugi')

@section('content')
<section class="p-10 bg-white shadow-lg border-gray-500 rounded-4xl">
  <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-gray-800">Detail Laba Rugi</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <div>
          <p class="text-sm text-gray-500">Tanggal</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              {{ \Carbon\Carbon::parse($labaRugi->tanggal)->translatedFormat('d F Y') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Jenis</p>
          <p class="mt-1 text-lg font-medium text-gray-800 capitalize">
              {{ $labaRugi->jenis }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Harga Pokok Jasa</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->harga_pokok_jasa, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Laba Kotor</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->laba_kotor, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Biaya Gaji</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->biaya_gaji, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Beban Meeting</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->beban_meeting, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Beban Lain-Lain</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->beban_lain_lain, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Beban Operasi</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->jumlah_beban_operasi, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Laba Bersih Operasional</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->laba_bersih_operasional, 0, ',', '.') }}
          </p>
      </div>

      <div>
          <p class="text-sm text-gray-500">Laba Bersih</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->laba_bersih, 0, ',', '.') }}
          </p>
      </div>

      <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Jumlah</p>
          <p class="mt-1 text-lg font-medium text-gray-800">
              Rp {{ number_format($labaRugi->jumlah, 0, ',', '.') }}
          </p>
      </div>
  </div>

  <div class="mt-8 flex justify-end space-x-2">
      <x-button variant="neutral" onclick="window.location='{{ route('laba-rugi.index') }}'">Kembali</x-button>
      @if(auth()->user()->role === 'akuntan')
          <x-button variant="edit" onclick="window.location='{{ route('laba-rugi.edit', $labaRugi->id) }}'">Edit</x-button>
      @endif
  </div>
</section>
@endsection
