@extends('layouts.main')
<style>
    body {
        font-family: 'Arial', sans-serif;
    }

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
@section('container')
    <div class="card">
        <div class="card-body">
            <div class="card-title">3. LAPORAN ARUS KAS</div>


            <!-- Pendapatan Section -->
            <div class="report-section">
                <table class="table-report">


                    <tr class="fw-bold border-bottom">
                        <td colspan="2">Kas Awal (1 Januari)</td>
                        <td class="text-end">2024</td>
                        <td class="text-end red-text">
                            {{ isset($buku_umum->where('transaksi', 'Kas Awal')->first()->nilai) ? formatRupiah($buku_umum->where('transaksi', 'Kas Awal')->first()->nilai) : 'Rp0' }}
                        </td>
                    </tr>


                    <tr>
                        <td>Kas masuk operasional</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($masuk->where('jenis_dana', 'operasional')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr>
                        <td>Kas masuk investasi</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($masuk->where('jenis_dana', 'investasi')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr>
                        <td>Kas masuk pendanaan</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($masuk->where('jenis_dana', 'pendanaan')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr class="fw-bold border-bottom">
                        <td>Total uang masuk</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($masuk->sum('nilai')) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Kas keluar operasional</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($keluar->where('jenis_dana', 'operasional')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr>
                        <td>Kas keluar investasi</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($keluar->where('jenis_dana', 'investasi')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr>
                        <td>Kas keluar pendanaan</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($keluar->where('jenis_dana', 'pendapatan')->sum('nilai')) }}
                        </td>

                    </tr>
                    <tr class="fw-bold border-bottom">
                        <td>Total uang keluar</td>
                        <td class="text-end"></td>
                        <td class="text-end">{{ formatRupiah($keluar->sum('nilai')) }}
                        </td>
                    </tr>
                    <tr class="fw-bold border-bottom">
                        <td colspan="2">Perubahan Kas</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($perubahan_kas) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td colspan="2">Kas Akhir</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">
                            {{ formatRupiah($kas_akhir) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection