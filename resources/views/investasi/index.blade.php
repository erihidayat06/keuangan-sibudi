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
                            <h5 class="card-title">Total <span>| Investasi</span></h5>

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

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Data Aset
                    </div>

                    <a href="/aset/investasi/create" class="btn btn-sm btn-primary mb-3">Tambah Data</a>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item Investasi</th>
                                <th scope="col">Tanggal Beli</th>
                                <th scope="col">Jumlah</th>
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
                                    $penyusutan = $aset->nilai / $aset->wkt_ekonomis;
                                    $saat_ini =
                                        $aset->nilai * $aset->jumlah - $aset->masa_pakai * $penyusutan * $aset->jumlah;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $aset->item }}</td>
                                    <td>{{ $aset->tgl_beli }}</td>
                                    <td>{{ $aset->jumlah }}</td>
                                    <td>{{ formatRupiah($aset->nilai) }}</td> <!-- Format nilai dengan formatRupiah -->
                                    <td>{{ $aset->wkt_ekonomis }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between">

                                            <form action="/aset/investasi/pakai/{{ $aset->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="pakai" value="kurang">
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="bi bi-arrow-down-circle"></i></button>
                                            </form>
                                            {{ $aset->masa_pakai == null ? 0 : $aset->masa_pakai }}
                                            <form action="/aset/investasi/pakai/{{ $aset->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="pakai" value="tambah">
                                                <button type="submit" class="btn btn-sm btn-success"><i
                                                        class="bi bi-arrow-up-circle"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                    <td>{{ formatRupiah($penyusutan) }}</td>
                                    <td>{{ formatRupiah($saat_ini) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a href="/aset/investasi/{{ $aset->id }}/edit"
                                                class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i></a>
                                            <form action="/aset/investasi/{{ $aset->id }}" class="ms-2"
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
