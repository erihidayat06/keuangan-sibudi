@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Perhitungan Laba Rugi Bulanan</h1>
            </div>


            <div class="card  overflow-auto">
                <div class="card-body">
                    <a href="/export-pdf/rincian-laba-rugi" class="btn btn-danger mt-3"><i class="bi bi-filetype-pdf"></i>
                        PDF</a>
                    <table class="table table-bordered mt-4 ">
                        <thead>

                            <tr class="bg-primary text-light">
                                <th>#</th>
                                <th>Jenis Biaya</th>
                                <th>Januari</th>
                                <th>Februari</th>
                                <th>Maret</th>
                                <th>April</th>
                                <th>Mei</th>
                                <th>Juni</th>
                                <th>Juli</th>
                                <th>Agustus</th>
                                <th>September</th>
                                <th>Oktober</th>
                                <th>November</th>
                                <th>Desember</th>
                                <th>Akumulasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="15" class="fw-bold">Pendapatan</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>{{ isset(unitUsaha()->unt_usaha1) ? unitUsaha()->unt_usaha1 : 'Unit 1' }}</td>
                                @foreach ($pendapatan['pu1'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['pu1']) }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>{{ isset(unitUsaha()->unt_usaha2) ? unitUsaha()->unt_usaha2 : 'Unit 2' }}</td>

                                @foreach ($pendapatan['pu2'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['pu2']) }}</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>{{ isset(unitUsaha()->unt_usaha3) ? unitUsaha()->unt_usaha3 : 'Unit 3' }}</td>
                                @foreach ($pendapatan['pu3'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['pu3']) }}</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>{{ isset(unitUsaha()->unt_usaha4) ? unitUsaha()->unt_usaha4 : 'Unit 4' }}</td>
                                @foreach ($pendapatan['pu4'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['pu4']) }}</td>
                            </tr>

                            <tr class="fw-bold bg-success text-light">
                                <td colspan="2">Total_Pendapatan</td>
                                @foreach ($pendapatanBulan['pu'] as $total)
                                    <td>{{ formatRupiah($total) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['pu']) }}</td>
                            </tr>

                            <tr>
                                <td colspan="15" class="fw-bold">Biaya Oprasional (Verbal)</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Biaya oprasional unit 1</td>
                                @foreach ($pendapatan['bo1'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bo1']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Biaya oprasional unit 2</td>
                                @foreach ($pendapatan['bo2'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bo2']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Biaya oprasional unit 3</td>
                                @foreach ($pendapatan['bo3'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bo3']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Biaya oprasional unit 4</td>
                                @foreach ($pendapatan['bo4'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bo4']) }}</td>
                            </tr>
                            <tr class="bg-info fw-bold">
                                <td colspan="2">Total_Biaya_Operasional</td>
                                @foreach ($pendapatanBulan['bo'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['bo']) }}</td>
                            </tr>
                            <tr>
                                <td colspan="15" class="fw-bold">Biaya Non Oprasional Tetap</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Gaji Pengurus</td>
                                @foreach ($pendapatan['bno1'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno1']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Atk</td>
                                @foreach ($pendapatan['bno2'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno2']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Rapat-rapat</td>
                                @foreach ($pendapatan['bno3'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno3']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Lain-lain</td>
                                @foreach ($pendapatan['bno4'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($tahun['bno4']) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Akumulasi Penyusutan</td>
                                @foreach ($pendapatan['bno5'] as $value)
                                    <td>-</td>
                                @endforeach
                                <td>{{ formatRupiah($akumulasi_penyusutan) }}</td>
                            </tr>
                            <tr class="bg-info fw-bold">
                                <td colspan="2">Total_Biaya_Non_Operasional</td>
                                @foreach ($pendapatanBulan['bno'] as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($pendapatanTahun['bno'] + $akumulasi_penyusutan) }}</td>
                            </tr>
                            <tr class="bg-warning fw-bold">
                                <td colspan="2">Total Biaya</td>

                                @foreach ($totalBiaya as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($akumulasiBiaya) }}</td>
                            </tr>

                            <tr class="bg-secondary fw-bold text-white">
                                <td colspan="2"> {{ $totalLabaRugi < 0 ? 'Rugi' : 'Laba' }}</td>

                                @foreach ($labaRugi as $value)
                                    <td>{{ formatRupiah($value) }}</td>
                                @endforeach
                                <td>{{ formatRupiah($totalLabaRugi) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
