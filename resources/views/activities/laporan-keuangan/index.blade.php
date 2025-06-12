@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Laporan Keuangan')

@section('content')
@php
    use App\Enums\Bulan;
@endphp
<section x-data="{ openTambah: false, openEdit: false, openHapus: false, targetId: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Laporan Keuangan</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
      <x-button variant="primary" type="button" @click="openTambah = true">
        Tambah Laporan Keuangan
      </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">
      <input name="search" type="text" id="search" placeholder="Cari Laporan Keuangan..."
      onkeyup="ajaxSearch()"
      class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class="bg-white shadow rounded-lg lg:max-w-[1056px]">
        <div id="scrollAbleTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laporan Keuangan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataLaporanKeuangan" class="bg-white divide-y divide-gray-200">
                    @foreach($laporanKeuangan as $i => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $bulanFormatted = ucfirst($item->updated_at->locale('id')->translatedFormat('F'));
                                    $bulanEnum = collect(Bulan::cases())->firstWhere('value', $bulanFormatted);
                                @endphp
                                {{ $bulanEnum = Bulan::from($bulanFormatted); }}
                            </td>
                            <td class="px-6 py-4 text-sm">{{ basename($item->file_laporan_keuangan) }}</td>
                            <td class="px-6 py-4 text-center text-sm space-x-1">
                                <a href="{{ route('laporan-keuangan.show', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(auth()->user()->role === 'akuntan')
                                    <button @click="openEdit = true; targetId = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</button>
                                    <button @click="openHapus = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $laporanKeuangan->links() }}
        </div>
    </div>

    <!-- Modal Tambah -->
    <template x-if="openTambah">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-blue-700">Tambah File Laporan Keuangan</h2>
                <p>Masukkan file laporan keuangan Anda!</p>
                <p class="text-gray-400 text-xs mb-6">Ukuran File Tidak Melebihi 5MB</p>
                <form action="{{ route('laporan-keuangan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <x-form type="file" id="laporan_keuangan" label="Laporan Keuangan" name="file_laporan_keuangan" required />
                    <div class="flex justify-end space-x-2">
                        <button @click.prevent="openTambah = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Modal Edit -->
    <template x-if="openEdit">
      <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 w-96">
              <h2 class="text-xl font-semibold mb-4 text-yellow-600">Edit File Laporan Keuangan</h2>
              <p>Unggah ulang file laporan keuangan Anda (jika diperlukan).</p>
              <p class="text-gray-400 text-xs mb-6">Ukuran File Tidak Melebihi 5MB</p>

              <form :action="'/laporan-keuangan/' + targetId" method="POST" enctype="multipart/form-data" class="space-y-4">
                  @csrf
                  @method('PUT')

                  <x-form type="file" id="file_laporan_keuangan_edit" label="Laporan Keuangan" name="file_laporan_keuangan" />

                  <div class="flex justify-end space-x-2">
                      <button @click.prevent="openEdit = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                      <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Update</button>
                  </div>
              </form>
          </div>
      </div>
    </template>


    <!-- Modal Konfirmasi Hapus -->
    <template x-if="openHapus">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
                <form :action="'/laporan-keuangan/' + target" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="flex justify-end space-x-2">
                        <button @click.prevent="openHapus = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</section>

{{-- Script AJAX dan Checkbox masih bisa dipakai --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    let searchTimer;

    window.ajaxSearch = function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const keyword = document.getElementById('search').value;
            const url = "{{ route('laporan-keuangan.search') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`${url}?keyword=${encodeURIComponent(keyword)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                let tbody = document.getElementById('dataLaporanKeuangan');
                tbody.innerHTML = "";

                data.forEach((item, index) => {
                    let tr = document.createElement('tr');
                    tr.classList.add('hover:bg-gray-50');

                    let aksiButtons = `
                        <a href="/laporan-keuangan/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                    `;

                    if ({{ auth()->user()->role === 'akuntan' ? 'true' : 'false' }}) {
                        aksiButtons += `
                            <a href="/laporan-keuangan/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                            <button @click="openHapus = true; target = '${item.id}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                        `;
                    }

                    tr.innerHTML = `
                        <td class="px-6 py-4 text-sm text-gray-700">${index + 1}</td>
                        <td class="px-6 py-4 text-sm">${item.bulan}</td>
                        <td class="px-6 py-4 text-sm">${item.file_laporan_keuangan}</td>
                        <td class="px-6 py-4 text-center text-sm space-x-1">${aksiButtons}</td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(error => console.error('Error:', error));
        }, 500);
    };
});
</script>
@endsection
