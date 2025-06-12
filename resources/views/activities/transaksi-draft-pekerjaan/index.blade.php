@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Transaksi Draft Pekerjaan')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Transaksi Draft Pekerjaan</h1>

    {{-- Tombol tambah transaksi jika user memiliki role yang sesuai --}}
    {{-- @if(in_array(auth()->user()->role, ['akuntan', 'pengawas']))
        <div class="mb-6">
            <x-button variant="primary" type="button" onclick="window.location='{{ route('draft-pekerjaan.create') }}'">
                Tambah Transaksi Draft Pekerjaan
            </x-button>
        </div>
    @endif --}}

    <div class="mb-4 w-full flex justify-end">
        <input name="search" type="text" id="search" placeholder="Cari Transaksi Draft Pekerjaan..."
               onkeyup="ajaxSearch()"
               class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class="bg-white shadow rounded-lg lg:max-w-[1056px]">
        <div id="scrollAbleTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code Draft</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instansi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai DPP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai PPN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai PPH Final</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai Bersih Pekerjaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataTransaksiDraftPekerjaan" class="bg-white divide-y divide-gray-200">
                    @foreach($transaksiDP as $i => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->code_draft ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->nama_pekerjaan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item->instansi ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ number_format($item->nilai_pekerjaan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">{{ number_format($item->nilai_dpp, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">{{ number_format($item->nilai_ppn, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">{{ number_format($item->nilai_pph_final, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">{{ number_format($item->nilai_bersih_pekerjaan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                                <a href="{{ route('transaksi-draft-pekerjaan.show',['transaksi_draft_pekerjaan' => $item->id]) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(in_array(auth()->user()->role, ['akuntan', 'pengawas']))
                                    <a href="{{ route('transaksi-draft-pekerjaan.edit', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                    {{-- <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">
                                        Hapus
                                    </button> --}}
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $transaksiDP->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    {{-- <template x-if="open">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <form :action="'/transaksi-draft-pekerjaan/' + target" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </template> --}}
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let searchTimer;

    window.ajaxSearch = function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const keyword = document.getElementById('search').value;
            const url = "{{ route('transaksi-draft-pekerjaan.search') }}";
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
                let tbody = document.getElementById('dataTransaksiDraftPekerjaan');
                tbody.innerHTML = ""; // Kosongkan isi tabel sebelum memperbarui

                if (data.length > 0) {
                    data.forEach((item, index) => {
                        // Bangun tombol aksi
                        let aksiButtons = `<a href="/transaksi-draft-pekerjaan/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>`;
                        @if(in_array(auth()->user()->role, ['akuntan', 'pengawas']))
                        aksiButtons += `
                            <a href="/transaksi-draft-pekerjaan/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                            <button @click="open = true; target = '${item.id}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                        `;
                        @endif

                        // Asumsi data transaksi memiliki properti terkait draft pada key "draft"
                        const draft = item.draft || {};

                        // Bangun row
                        let tr = document.createElement('tr');
                        tr.classList.add('hover:bg-gray-50');
                        tr.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox" class="w-5 h-5 rowCheckbox" data-row="${item.id}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${index + 1}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${draft.code_draft ? draft.code_draft : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${draft.nama_pekerjaan ? draft.nama_pekerjaan : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${draft.instansi ? draft.instansi : '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${parseFloat(item.nilai_pekerjaan).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${parseFloat(item.nilai_dpp).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${parseFloat(item.nilai_ppn).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${parseFloat(item.nilai_pph_final).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${parseFloat(item.nilai_bersih_pekerjaan).toLocaleString(undefined, {minimumFractionDigits:2})}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                                ${aksiButtons}
                            </td>
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

<!-- Script update checkbox (jika ada update field dengan ajax) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".rowCheckbox").forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
            const id = this.dataset.row;
            const name = this.name ? this.name.replace('[]', '') : '';
            const value = this.checked ? 1 : 0;

            fetch(`/transaksi-draft-pekerjaan/update-checkbox/${id}`, {
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
