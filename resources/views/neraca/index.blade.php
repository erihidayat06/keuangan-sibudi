@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">1. Laporan Neracha</div>

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
                                <td>Laba/Rugi ditahan <strong>
                                        {{ $ditahan < 0 ? '(Rugi)' : '(Untung)' }}</strong></td>
                                <td>{{ formatRupiah($ditahan) }}</td>
                            </tr>
                            <tr>
                                <td>Investasi</td>
                                <td>{{ formatRupiah($investasi) }}</td>
                                <td>Laba/Rugi Berjalan <strong>
                                        {{ $laba_rugi_berjalan < 0 ? '(Rugi)' : '(Untung)' }}</strong></td>
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
@endsection
