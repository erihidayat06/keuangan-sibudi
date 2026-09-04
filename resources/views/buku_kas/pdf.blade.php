<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }

    .row {
        width: 100%;
    }

    .modal {
        font-size: 25px;
        font-weight: bold;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table,
    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 5px;
        font-size: 11px;
    }

    th {
        background-color: #f2f2f2;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-success {
        color: #198754;
    }

    .text-danger {
        color: #dc3545;
    }

    .text-warning {
        color: #ffc107;
    }

    .text-muted {
        color: #6c757d;
    }

    .fw-bold {
        font-weight: bold;
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h2>Rincian Buku Kas</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col" style="width: 30px;">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Transaksi</th>
                                <th scope="col">(Masuk/Keluar)</th>
                                <th scope="col">Jenis Transaksi</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Saldo</th>
                                <th scope="col">Pembukuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                                $saldo = 0;
                            @endphp

                            @if ($saldo_lalu != 0)
                                @php
                                    $saldo = $saldo_lalu;
                                @endphp
                                <tr>
                                    <th scope="row" class="text-center">{{ $i++ }}</th>
                                    <td>1-01-{{ session('selected_year', date('Y')) }}</td>
                                    <td>Saldo Awal</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">{{ formatRupiah($saldo) }}</td>
                                    <td></td>
                                </tr>
                            @endif

                            @foreach ($transaksis as $transaksi)
                                @php
                                    if ($transaksi->jenis == 'debit') {
                                        $saldo += $transaksi->nilai;
                                    } elseif ($transaksi->jenis == 'kredit') {
                                        $saldo -= $transaksi->nilai;
                                    }
                                @endphp
                                <tr>
                                    <th scope="row" class="text-center">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($transaksi->tanggal) }}</td>
                                    <td>{{ $transaksi->transaksi }}</td>
                                    <td
                                        class="fw-bold {{ $transaksi->jenis == 'debit' ? 'text-success' : ($transaksi->jenis == 'kredit' ? 'text-danger' : 'text-warning') }}">
                                        {{ $transaksi->jenis == 'debit' ? 'Masuk' : ($transaksi->jenis == 'kredit' ? 'Keluar' : 'Tetap') }}
                                    </td>
                                    <td>
                                        @if ($transaksi->jenis_dana == 'tidak_dihitung')
                                            <span class="text-muted fw-bold">Tidak Dihitung</span>
                                        @else
                                            <span
                                                class="fw-bold {{ $transaksi->jenis == 'debit' ? 'text-success' : ($transaksi->jenis == 'kredit' ? 'text-danger' : 'text-warning') }}">
                                                {{ ucfirst($transaksi->jenis_dana) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-right">{{ formatRupiah($transaksi->nilai) }}</td>
                                    <td class="text-right">{{ formatRupiah($saldo) }}</td>
                                    <td>
                                        @foreach (namaUnitUsaha() as $key => $value)
                                            @if ($transaksi->jenis_lr == $key)
                                                {{ $value }}
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach

                            <tr style="background-color: #fff3cd;" class="fw-bold">
                                <td colspan="6" class="text-center">Total Saldo</td>
                                <td class="text-right">{{ formatRupiah($saldo) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
