<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan PDF</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <h2>Laporan Keuangan: {{ $laporan->laporan_keuangan }}</h2>
    <p>Status: {{ ucfirst($laporan->status_laporan) }}</p>
    <p>Tanggal: {{ $laporan->created_at->format('d M Y') }}</p>

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

        <h3>{{ $label }}</h3>

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
