<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .table-report {
            width: 100%;
            border-collapse: collapse;
        }

        .table-report th,
        .table-report td {
            font-size: 14px;
            padding: 4px 8px;
        }

        .table-report th {
            text-align: left;
        }

        .red-text {
            color: red;
        }

        .green-text {
            color: rgb(5, 192, 67);
        }

        .yellow-text {
            color: rgb(175, 173, 19);
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .ms {
            margin-left: 15px;
        }

        .pt-2 {
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="card">
        <table class="table-report">
            <tr>
                <th colspan="4" style="font-size: 16px; padding-bottom: 10px;">2. LAPORAN LABA RUGI</th>
            </tr>

            <!-- 1. PENDAPATAN SECTION -->
            <tr>
                <th colspan="4" class="pt-2">1. PENDAPATAN UNIT USAHA</th>
            </tr>
            @foreach ($units as $unit)
                @php
                    $kodeClean = str_replace(' ', '', strtolower($unit->kode));
                @endphp
                <tr>
                    <td>
                        <span class="ms">{{ $unit->nm_unit }}</span>
                    </td>
                    <td class="text-end"></td>
                    <td class="text-end red-text">
                        {{ formatRupiah(array_sum($pendapatan['pu' . $kodeClean] ?? [])) }}
                    </td>
                    <td></td>
                </tr>
            @endforeach

            <tr class="fw-bold border-bottom">
                <td colspan="2">Total Seluruh Pendapatan</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu'] ?? 0) }}</td>
            </tr>

            <!-- 2. HARGA POKOK PENJUALAN (HPP) SECTION -->
            <tr class="fw-bold border-bottom">
                <th colspan="2" class="pt-2">2. HARGA POKOK PENJUALAN (HPP)</th>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah($pendapatanTahun['hpp'] ?? 0) }}
                </td>
            </tr>



            <!-- 4. BIAYA OPERASIONAL & NON OPERASIONAL -->
            <tr>
                <th colspan="4" class="pt-2">3. BIAYA OPERASIONAL</th>
            </tr>
            @foreach ($units as $unit)
                @php
                    $kodeClean = str_replace(' ', '', strtolower($unit->kode));
                @endphp
                <tr>
                    <td><span class="ms">Biaya Ops {{ $unit->nm_unit }}</span></td>
                    <td class="text-end"></td>
                    <td class="text-end red-text">
                        {{ formatRupiah(array_sum($pendapatan['bo' . $kodeClean] ?? [])) }}
                    </td>
                    <td></td>
                </tr>
            @endforeach

            <tr class="fw-bold border-bottom">
                <td>Total Biaya Operasional</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo'] ?? 0) }}</td>
                <td></td>
            </tr>

            <!-- BIAYA NON OPERASIONAL -->
            <tr>
                <th colspan="4" class="pt-2">Biaya Non Operasional</th>
            </tr>
            <tr>
                <td><span class="ms">Gaji/Honor Pengurus</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'] ?? [])) }}</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="ms">ATK dan Fotocopy</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'] ?? [])) }}</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="ms">Rapat-rapat</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'] ?? [])) }}</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="ms">Akumulasi Penyusutan</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan ?? 0) }}</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="ms">Lain-lain</span></td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'] ?? [])) }}</td>
                <td></td>
            </tr>
            <tr class="fw-bold border-bottom">
                <td>Total Biaya Non Operasional</td>
                <td class="text-end"></td>
                <td class="text-end red-text">
                    {{ formatRupiah(($pendapatanTahun['bno'] ?? 0) + ($akumulasi_penyusutan ?? 0)) }}
                </td>
                <td></td>
            </tr>
            <tr class="fw-bold border-bottom">
                <td colspan="2">Total Seluruh Biaya Operasional</td>
                <td class="text-end"></td>
                <td class="text-end red-text">{{ formatRupiah($akumulasiBiaya ?? 0) }}</td>
            </tr>

            <!-- 5. LABA / RUGI BERSIH -->
            <tr class="fw-bold">
                <td colspan="2">
                    <span class="{{ ($totalLabaRugi ?? 0) < 0 ? 'yellow-text' : 'green-text' }}">
                        Total {{ ($totalLabaRugi ?? 0) < 0 ? 'Rugi' : 'Laba' }} Bersih Berjalan
                    </span>
                </td>
                <td class="text-end"></td>
                <td class="text-end {{ ($totalLabaRugi ?? 0) < 0 ? 'yellow-text' : 'green-text' }}">
                    {{ formatRupiah($totalLabaRugi ?? 0) }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
