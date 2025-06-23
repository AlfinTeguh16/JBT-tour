<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <style>
        td, th {
            border: 1px solid #000;
            padding: 4px;
        }
        table {
            border-collapse: collapse;
            margin-bottom: 30px;
            width: 100%;
        }
        th {
            background-color: #f0f0f0;
        }
        .title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

    <h3 class="title">Laporan Keuangan: {{ $laporan->laporan_keuangan }}</h3>
    <p>Status: {{ ucfirst($laporan->status_laporan) }}</p>
    <p>Tanggal Dibuat: {{ $laporan->created_at->format('d M Y') }}</p>

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
        @php
            $data = $$var;
        @endphp

        <h4>{{ $label }}</h4>

        @if ($data->isEmpty())
            <p>Tidak ada data.</p>
        @else
            @php
                $headers = collect(array_keys($data->first()->getAttributes()))
                            ->reject(fn($key) => in_array($key, $hiddenCols))
                            ->prepend('No');

                $totals = [];
                foreach ($headers as $key) {
                    if ($key !== 'No' && !str_contains($key, '_id')) {
                        $totals[$key] = $data->sum(function($item) use ($key) {
                            return is_numeric($item->$key) ? $item->$key : 0;
                        });
                    }
                }
            @endphp

            <table>
                <thead>
                    <tr>
                        @foreach ($headers as $key)
                            <th>{{ $key === 'No' ? 'No' : ucwords(str_replace('_', ' ', $key)) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $row)
                        <tr>
                            @foreach ($headers as $key)
                                <td>
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
                <tfoot>
                    <tr>
                        @foreach ($headers as $key)
                            <td>
                                @if ($key === 'No')
                                    Total
                                @elseif (isset($totals[$key]) && $totals[$key] > 0)
                                    {{ number_format($totals[$key], 0, ',', '.') }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        @endif
    @endforeach

</body>
</html>
