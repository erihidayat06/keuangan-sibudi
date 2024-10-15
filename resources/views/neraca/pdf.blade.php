<style>
    .row {
        font-family: Arial, Helvetica, sans-serif
    }

    /* Gaya untuk card title */
    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1rem;
    }

    /* Gaya umum untuk tabel */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: #fff;
        border-collapse: collapse;
    }

    /* Gaya untuk border tabel */
    .table th,
    .table td {
        padding: 12px;
        vertical-align: middle;

    }


    /* Kolom yang mencakup beberapa kolom */
    .table thead th[colspan] {
        text-align: center;
    }

    /* Gaya untuk baris total */
    .table .fw-bold {
        font-weight: 700;
        background-color: #ffd900;
    }

    /* Gaya untuk perbedaan untung/rugi */
    .table td strong {
        font-weight: bold;
        color: #dc3545;
        /* Warna merah jika rugi */
    }

    /* Responsivitas untuk perangkat mobile */
    @media (max-width: 767.98px) {

        .table th,
        .table td {
            padding: 8px;
            font-size: 12px;
        }

        .card-title {
            font-size: 1.25rem;
        }
    }
</style>


<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">1. Laporan Neracha</h2>

                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th colspan="2">
                                Aktiva
                            </th>
                            <th colspan="2">
                                Passiva
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Kas atau setara Kas</td>
                            <td>{{ formatRupiah($kas) }}</td>
                            <td>Hutang</td>
                            <td>{{ formatRupiah($hutang) }}</td>
                        </tr>
                        <tr>
                            <td>Piutang</td>
                            <td>{{ formatRupiah($piutang) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Saldo Pinjam</td>
                            <td>{{ formatRupiah($saldo_pinjam) }}</td>
                            <td>Pernyertaan Modal Desa</td>
                            <td>{{ formatRupiah($modal_desa) }}</td>
                        </tr>
                        <tr>
                            <td>Persediaan dagang</td>
                            <td>{{ formatRupiah($persediaan_dagang) }}</td>
                            <td>Pernyertaan Modal Masyarakat</td>
                            <td>{{ formatRupiah($modal_masyarakat) }}</td>
                        </tr>
                        <tr>
                            <td>Biaya Dibayar di muka</td>
                            <td>{{ formatRupiah($bayar_dimuka) }}</td>
                            <td>{{ $ditahan < 0 ? 'Rugi' : 'Laba' }} ditahan </td>
                            <td>{{ formatRupiah($ditahan) }}</td>
                        </tr>
                        <tr>
                            <td>Investasi</td>
                            <td>{{ formatRupiah($investasi) }}</td>
                            <td>{{ $laba_rugi_berjalan < 0 ? 'Rugi' : 'Laba' }} Berjalan</td>
                            <td>{{ formatRupiah($laba_rugi_berjalan) }}</td>
                        </tr>
                        <tr>
                            <td>Bangunan</td>
                            <td>{{ formatRupiah($bangunan) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Aktiva Lain</td>
                            <td>{{ formatRupiah($aktiva_lain) }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Total Aktiva</td>
                            <td>{{ formatRupiah($total_aktiva) }}</td>
                            <td>Total Pasiva</td>
                            <td>{{ formatRupiah($passiva) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
