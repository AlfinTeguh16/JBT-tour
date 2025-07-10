@extends('layouts.master')
@section('title', 'Detail Laporan Keuangan')
@section('content')

<div class=" mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Detail Laporan Keuangan</h1>

    <div class="bg-white shadow rounded p-4 mb-6">
        <h2 class="text-lg font-semibold mb-2">Informasi Utama</h2>
        <p><strong>Nama Laporan:</strong> Laporan Bulan {{ $laporan->created_at->format('m Y') ?? '-' }}</p>
        <p><strong>Status Laporan:</strong> {{ ucfirst($laporan->status_laporan) }}</p>
        <p><strong>Dibuat pada:</strong> {{ $laporan->created_at->format('d M Y') }}</p>
        @if(auth()->user()->role === 'direktur')
        <div class="mb-6">
            <form action="{{ route('laporan-keuangan.update-status', $laporan->id) }}" method="post">
                @csrf
                @method('PUT')
                <select name="status_laporan" id="status_laporan" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="belum tervalidasi">Belum Tervalidasi</option>
                    <option value="tervalidasi">Tervalidasi</option>
                </select>
                <x-button type="submit" variant="primary" class="ml-2 px-4 py-2">
                    Simpan Status
                </x-button>
            </form>
        </div>
        @endif
    </div>
    @if($laporan->status_laporan === 'tervalidasi')
    <div class="flex gap-4 mt-4">
        <a href="{{ route('laporan-keuangan.export.pdf', $laporan->id) }}"
        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Unduh PDF
        </a>
        <a href="{{ route('laporan-keuangan.export.excel', $laporan->id) }}"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Unduh Excel
        </a>
    </div>
    @endif


    @php
        $sections = [
            'draftPekerjaan'    => 'Draft Pekerjaan',
            'transaksiDraft'    => 'Transaksi Draft Pekerjaan',
            'arusKas'           => 'Arus Kas',
            'labaRugi'          => 'Laba Rugi',
            'perubahanModal'    => 'Perubahan Modal',
            'neraca'            => 'Data Neraca',
            'jurnalUmum'        => 'Jurnal Umum Bulan Ini',
        ];

        $hiddenCols = ['id', 'is_deleted', 'created_at', 'updated_at'];
    @endphp

    @foreach ($sections as $var => $label)
        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-lg font-semibold mb-2">{{ $label }}</h2>

            @if ($$var->isEmpty())
                <p class="text-gray-500">Tidak ada data.</p>
            @else
                @php
                    $headers = collect(array_keys($$var->first()->getAttributes()))
                                ->reject(fn($key) => in_array($key, $hiddenCols))
                                ->prepend('No');

                    // Hitung total numerik, kecuali kolom yang mengandung '_id'
                    $totals = [];
                    foreach ($headers as $key) {
                        if ($key !== 'No' && !str_contains($key, '_id')) {
                            $totals[$key] = $$var->sum(function($item) use ($key) {
                                return is_numeric($item->$key) ? $item->$key : 0;
                            });
                        }
                    }
                @endphp

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                @foreach ($headers as $key)
                                    <th class="px-4 py-2 border">{{ $key === 'No' ? 'No' : ucwords(str_replace('_', ' ', $key)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($$var as $i => $row)
                                <tr class="border-t">
                                    @foreach ($headers as $key)
                                        <td class="px-4 py-2 border">
                                            @if ($key === 'No')
                                                {{ $i + 1 }}
                                            @else
                                                @php $val = $row->$key; @endphp
                                                {{ is_numeric($val) ? number_format($val, 0, ',', '.') : $val }}
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-semibold">
                            <tr>
                                @foreach ($headers as $key)
                                    <td class="px-4 py-2 border">
                                        @if ($key === 'No')
                                            Total
                                        @elseif (isset($totals[$key]) && $totals[$key] > 0)
                                            Rp {{ number_format($totals[$key], 0, ',', '.') }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    @endforeach

    <a href="{{ route('laporan-keuangan.index') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Kembali
    </a>
</div>
@endsection
