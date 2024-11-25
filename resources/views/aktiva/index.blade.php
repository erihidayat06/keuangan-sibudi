@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <!-- Nilai Akumulasi Penyusutan Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Akumulasi Penyusutan</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($akumulasi) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Akumulasi Penyusutan Card -->
                <!-- Nilai Investasi Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Inventaris</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="ps-3">
                                    <h6>{{ formatRupiah($investasi) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Nilai Investasi Card -->
            </div>

            <div class="card overflow-auto">
                <div class="card-body">
                    <div class="card-title">
                        Data Aset
                    </div>


                    <div class="row cols-2 cols-lg-2">
                        <div class="col">

                            <a href="/aset/aktivalain/create" class="btn btn-sm btn-primary mb-3">Tambah Data</a>

                        </div>
                        <div class="col text-end">
                            <a href="/export-pdf/aktivalain" class="btn btn-danger"><i class="bi bi-filetype-pdf"></i>
                                PDF</a>
                        </div>
                    </div>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jenis aktiva</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Waktu Ekonomis</th>
                                <th scope="col">Masa Pakai</th>
                                <th scope="col">Penyusutan</th>
                                <th scope="col">Nilai_Saat_Ini</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($asets as $aset)
                                @php
                                    $masaPakai = masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'];
                                    $jumlah_penyusutan = 0;
                                    if ($masaPakai) {
                                        $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                                    } else {
                                        $penyusutan = 0;
                                    }

                                    if ($masaPakai == $aset->wkt_ekonomis) {
                                        $jumlah_penyusutan = 0;
                                    } else {
                                        $jumlah_penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                                    }

                                    $saat_ini =
                                        $aset->nilai -
                                        masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] * $penyusutan;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ formatTanggal($aset->created_at) }}</td>
                                    <td>{{ $aset->jenis }}</td>
                                    <td>{{ formatRupiah($aset->nilai) }}</td> <!-- Format nilai dengan formatRupiah -->
                                    <td>{{ $aset->wkt_ekonomis }}</td>
                                    <td>
                                        {{ masaPakai($aset->created_at, $aset->wkt_ekonomis)['masa_pakai'] }}
                                    </td>
                                    <td>{{ formatRupiah($jumlah_penyusutan) }}</td>
                                    <td>{{ formatRupiah($saat_ini) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/aset/aktivalain/{{ $aset->id }}/edit"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i></a>
                                            <form action="/aset/aktivalain/{{ $aset->id }}" class="ms-2"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin dihapus?')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
