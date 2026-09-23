@extends('layouts.main')

@section('container')
    <style>
        .report-section {
            margin-top: 20px;
        }

        .table-report {
            width: 100%;
            border-collapse: collapse;
        }

        .table-report th,
        .table-report td {
            padding: 5px 10px;
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

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .text-end {
            text-align: end;
        }

        .text-start {
            text-align: start;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
    <div class="card">
        <div class="card-body overflow-auto">
            <a href="/export-pdf/laporan-laba-rugi" class="btn btn-danger mt-3"><i class="bi bi-filetype-pdf"></i>
                PDF</a>
            <div class="card-title">2. LAPORAN LABA RUGI</div>

            <div class="report-section">
                <table class="table-report">
                    <!-- 1. PENDAPATAN SECTION -->
                    <tr>
                        <th colspan="4">1. PENDAPATAN UNIT USAHA</th>
                    </tr>
                    @foreach ($units as $unit)
                        @php
                            $kodeClean = str_replace(' ', '', strtolower($unit->kode));
                        @endphp
                        <tr>
                            <td>{{ $unit->nm_unit }}</td>
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
                        <th colspan="3" class="pt-3">2. HARGA POKOK PENJUALAN (HPP)</th>
                        <td class="text-end red-text fw-bold pt-3">
                            {{ formatRupiah($pendapatanTahun['hpp'] ?? 0) }}
                        </td>
                    </tr>



                    <!-- 4. BIAYA OPERASIONAL & NON OPERASIONAL -->
                    <tr>
                        <th colspan="4" class="pt-3">3. BIAYA-BIAYA OPERASIONAL</th>
                    </tr>
                    @foreach ($units as $unit)
                        @php
                            $kodeClean = str_replace(' ', '', strtolower($unit->kode));
                        @endphp
                        <tr>
                            <td>Biaya Ops {{ $unit->nm_unit }}</td>
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
                        <td>Gaji/Honor Pengurus</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'] ?? [])) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>ATK dan Fotocopy</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'] ?? [])) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Rapat-rapat</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'] ?? [])) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Akumulasi Penyusutan</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan ?? 0) }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Lain-lain</td>
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
                    <tr class="fw-bold" style="font-size: 1.1em;">
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
        </div>
    </div>
@endsection
