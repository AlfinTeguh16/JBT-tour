@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Data Karyawan')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl ">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Data Karyawan</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
      <x-button variant="primary" type="button" onclick="window.location='{{ route('karyawan.create') }}'">
        Tambah Karyawan
      </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">
      <!-- Input search yang langsung memicu pencarian AJAX -->
      <input name="search" type="text" id="search" placeholder="Cari karyawan..."
      onkeyup="ajaxSearch()"
      class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class="w-screen lg:w-full overflow-x-auto border border-gray-200 rounded-lg">
        <div class=" bg-white shadow rounded-lg ">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Telepon</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataKaryawan" class="bg-white divide-y divide-gray-200">
                    @foreach($karyawan as $i => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->id_karyawan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->no_telepon }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                            <a href="{{ route('karyawan.show', $item) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                            @if(auth()->user()->role === 'akuntan')
                                <a href="{{ route('karyawan.edit', $item) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
            <div class="mt-4 flex justify-end">
                {{ $karyawan->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <template x-if="open">
      <div class="fixed inset-0 bg-gray-600/25  flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 w-96">
              <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
              <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
              <div class="flex justify-end space-x-2">
                  <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                  <form x-bind:action="'/karyawan/' + target" method="POST">
                      @csrf
                      @method('POST')
                      <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                  </form>
              </div>
          </div>
      </div>
    </template>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let searchTimer;

    window.ajaxSearch = function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const keyword = document.getElementById('search').value;
            const url = "{{ route('karyawan.search') }}";
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
                let tbody = document.getElementById('dataKaryawan');
                tbody.innerHTML = ""; // Kosongkan isi tabel sebelum memperbarui

                if (data.length > 0) {
                    data.forEach((item, index) => {
                        let tr = document.createElement('tr');
                        tr.classList.add('hover:bg-gray-50');

                        let aksiButtons = `
                            <a href="/karyawan/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                        `;

                        if (@json(auth()->user()->role === 'akuntan')) {
                            aksiButtons += `
                                <a href="/karyawan/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                <button @click="open = true; target = '${item.id}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            `;
                        }

                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.id_karyawan}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.nama}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.no_telepon}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">${aksiButtons}</td>
                        `;

                        tbody.appendChild(tr);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }, 500);
    };
});
</script>

@endsection
