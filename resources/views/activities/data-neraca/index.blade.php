@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Data Neraca')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Data Neraca</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
      <x-button variant="primary" type="button" onclick="window.location='{{ route('data-neraca.create') }}'">
        Tambah Data Neraca
      </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">

      <input name="search" type="text" id="search" placeholder="Cari Data Neraca..." 
      onkeyup="ajaxSearch()"
      class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class=" bg-white shadow rounded-lg lg:max-w-[1056px] ">
        <div id="scrollAbleTable" class="overflow-x-auto ">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Select All</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Draft Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaksi Draft Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Draft Pekerjaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataNeraca" class="bg-white divide-y divide-gray-200">
                    @foreach($neraca as $i => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox" class="w-full mx-auto selectAllRow form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded" 
                                    data-row="{{ $item->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <p>{{ $item->bulan }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="data_karyawan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->data_karyawan == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="draft_pekerjaan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->draft_pekerjaan == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="transaksi_draft_pekerjaan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->transaksi_draft_pekerjaan == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="status_transaksi[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->status_transaksi == 1)>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox"
                                    class="w-full mx-auto rowCheckbox form-checkbox h-5 text-primary focus:ring-primary border-gray-300 rounded"
                                    name="status_draft_pekerjaan[]" value="1"
                                    data-row="{{ $item->id }}"
                                    @checked($item->status_draft_pekerjaan == 1)>
                            </td>                         
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                                <a href="{{ route('data-neraca.show', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(auth()->user()->role === 'akuntan')
                                    <a href="{{ route('data-neraca.edit', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                    <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>    

            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $neraca->links() }}
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
                    <form x-bind:action="'/data-neraca/' + target" method="POST">
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
            const url = "{{ route('data-neraca.search') }}"; 
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
                let tbody = document.getElementById('dataDraftPekerjaan');
                tbody.innerHTML = ""; // Kosongkan isi tabel sebelum memperbarui

                if (data.length > 0) {
                    data.forEach((item, index) => {
                        let tr = document.createElement('tr');
                        tr.classList.add('hover:bg-gray-50');

                        let aksiButtons = `
                            <a href="/data-neraca/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                        `;

                        if (@json(auth()->user()->role === 'akuntan')) {
                            aksiButtons += `
                                <a href="/data-neraca/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                <button @click="open = true; target = '${item.id}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                    Hapus
                                </button>
                            `;
                        }

                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.id_draft-neraca}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${item.bulan}</td>
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


//untuk select 1 row
document.addEventListener("DOMContentLoaded", function () {
    // Saat checkbox "Select All" diklik
    document.querySelectorAll(".selectAllRow").forEach(selectAll => {
        selectAll.addEventListener("change", function () {
            let rowId = this.getAttribute("data-row");
            let checkboxes = document.querySelectorAll(`.rowCheckbox[data-row="${rowId}"]`);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    });
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".rowCheckbox").forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                const id = this.dataset.row;
                const name = this.name.replace('[]', ''); // ambil nama kolomnya
                const value = this.checked ? 1 : 0;
    
                fetch(`/data-neraca/update-checkbox/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ field: name, value: value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`Berhasil update ${name} untuk ID ${id}`);
                    } else {
                        alert("Gagal mengupdate data!");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan!");
                });
            });
        });
    });
    </script>
    

@endsection
