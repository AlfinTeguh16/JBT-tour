@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Data Laba Rugi')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Data Laba Rugi</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
        <x-button variant="primary" onclick="window.location='{{ route('laba-rugi.create') }}'">
            Tambah Data Laba Rugi
        </x-button>
    </div>
    @endif

    <div class="mb-4 flex justify-end">
        <input id="search" type="text" placeholder="Cari Data..."
            onkeyup="ajaxSearch()"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:ring-primary focus:border-primary"/>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-start">#</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-start">Tanggal</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-start">Jenis</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-start">Jumlah</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataLabaRugi" class=" divide-gray-200">
                    @forelse($labaRugi as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-2 text-sm capitalize">{{ $item->jenis }}</td>
                            <td class="px-4 py-2 text-sm">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center space-x-1">
                                <a href="{{ route('laba-rugi.show', $item->id) }}" class="inline-flex px-2 py-1 text-xs rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                                @if(auth()->user()->role === 'akuntan')
                                    <a href="{{ route('laba-rugi.edit', $item->id) }}" class="inline-flex px-2 py-1 text-xs rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                                    <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-sm text-gray-500 py-4">Data tidak ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $labaRugi->links() }}
        </div>
    </div>

    <!-- Modal Hapus -->
    <template x-if="open">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-lg font-semibold mb-4 text-red-600">Hapus Data</h2>
                <p class="mb-6">Yakin ingin menghapus data ini?</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <form :action="'/laba-rugi/' + target" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let searchTimer;
    window.ajaxSearch = function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            const keyword = document.getElementById('search').value;
            fetch(`{{ route('laba-rugi.search') }}?keyword=${encodeURIComponent(keyword)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('dataLabaRugi');
                tbody.innerHTML = "";
                if (data.length) {
                    data.forEach((item, index) => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">${index + 1}</td>
                                <td class="px-4 py-2 text-sm">${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
                                <td class="px-4 py-2 text-sm">${item.jenis}</td>
                                <td class="px-4 py-2 text-sm">Rp ${parseFloat(item.jumlah).toLocaleString('id-ID')}</td>
                                <td class="px-4 py-2 text-center space-x-1">
                                    <a href="/laba-rugi/${item.id}" class="inline-flex px-2 py-1 text-xs rounded bg-blue-500 text-white">Detail</a>
                                    @if(auth()->user()->role === 'akuntan')
                                    <a href="/laba-rugi/${item.id}/edit" class="inline-flex px-2 py-1 text-xs rounded bg-yellow-400 text-white">Edit</a>
                                    <button @click="open = true; target = ${item.id}" class="inline-flex px-2 py-1 text-xs rounded bg-red-500 text-white">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="text-center text-sm text-gray-500 py-4">Data tidak ditemukan</td></tr>`;
                }
            });
        }, 400);
    };
});
</script>
@endsection
