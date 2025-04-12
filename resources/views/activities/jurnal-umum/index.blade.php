@extends('layouts.master')

@section('title', 'CV. Cipta Arya - Jurnal Umum')

@section('content')
<section x-data="{ open: false, target: null }" class="p-10 bg-white shadow-lg rounded-4xl w-full">
    <h1 class="font-bold text-3xl text-gray-800 mb-6">Data Jurnal Umum</h1>

    @if(auth()->user()->role === 'akuntan')
    <div class="mb-6">
      <x-button variant="primary" type="button" onclick="window.location='{{ route('jurnal-umum.create') }}'">
        Tambah Jurnal Umum
      </x-button>
    </div>
    @endif

    <div class="mb-4 w-full flex justify-end">
        <input name="search" type="text" id="search" placeholder="Cari Jurnal Umum..." 
        onkeyup="ajaxSearch()"
        class="w-fit rounded-lg border border-gray-300 px-3 py-2 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"/>
    </div>

    <div class="bg-white shadow rounded-lg lg:max-w-full">
        <div id="scrollAbleTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akun Debet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akun Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataJurnalUmum" class="bg-white divide-y divide-gray-200">
                    @foreach($jurnal as $i => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->tanggal->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->keterangan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->akun_debet }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $item->akun_kredit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                            <a href="{{ route('jurnal-umum.show', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                            @if(auth()->user()->role === 'akuntan')
                            <a href="{{ route('jurnal-umum.edit', $item->id) }}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                            <button @click="open = true; target = '{{ $item->id }}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-end">
            {{ $jurnal->links() }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <template x-if="open">
        <div class="fixed inset-0 bg-gray-600/25 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h2 class="text-xl font-semibold mb-4 text-red-600">Konfirmasi Hapus</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-end space-x-2">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <form x-bind:action="'/jurnal-umum/' + target" method="POST">
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
function ajaxSearch() {
    let timer;
    clearTimeout(timer);
    timer = setTimeout(() => {
        const keyword = document.getElementById('search').value;
        const url = "{{ route('jurnal-umum.search') }}";
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
            const tbody = document.getElementById('dataJurnalUmum');
            tbody.innerHTML = "";

            if (data.length > 0) {
                data.forEach((item, index) => {
                    let tr = document.createElement('tr');
                    tr.classList.add('hover:bg-gray-50');

                    const formattedDate = new Date(item.tanggal).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${formattedDate}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.keterangan ?? '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.akun_debet}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${item.akun_kredit}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Rp ${Number(item.jumlah).toLocaleString('id-ID')}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-1">
                            <a href="/jurnal-umum/${item.id}" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-blue-500 text-white hover:bg-blue-600">Detail</a>
                            @if(auth()->user()->role === 'akuntan')
                            <a href="/jurnal-umum/${item.id}/edit" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-yellow-400 text-white hover:bg-yellow-500">Edit</a>
                            <button onclick="open = true; target = '${item.id}'" class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-500 text-white hover:bg-red-600">Hapus</button>
                            @endif
                        </td>`;

                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 py-4">Data tidak ditemukan.</td></tr>`;
            }
        })
        .catch(error => console.error('Error:', error));
    }, 500);
}
</script>

@endsection