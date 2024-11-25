<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .pagetitle {
        text-align: center;
    }

    .row-modal {
        margin: 0 -5px;
        width: 100%;
    }

    .card-modal {
        border: 1px solid black;
        float: left;
        width: 200px;
        padding: 0 10px;
        margin-right: 20px;
        border-radius: 10px;
    }

    .modal {
        font-size: 25px;
        font-weight: bold;
    }

    .card {}

    table {
        border-collapse: collapse;
        width: 100%
    }

    table,
    td,
    th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 5px;
        font-size: 12px
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h3>Rincian Aset Iventari</h3>
                <h3>{{ unitUsaha()->nm_bumdes }}</h3>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">
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

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($asets as $aset)
                                @php
                                    $masa_pakai = masaPakai($aset->tgl_beli, $aset->wkt_ekonomis)['masa_pakai'];
                                    $penyusutan = ($aset->nilai / $aset->wkt_ekonomis) * $aset->jumlah;
                                    $jumlah_penyusutan = 0;

                                    if ($masa_pakai == $aset->wkt_ekonomis) {
                                        $jumlah_penyusutan = 0;
                                    } else {
                                        $jumlah_penyusutan = ($aset->nilai / $aset->wkt_ekonomis) * $aset->jumlah;
                                    }
                                    $saat_ini = $aset->nilai * $aset->jumlah - $masa_pakai * $penyusutan;
                                @endphp
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $aset->item }}</td>
                                    <td>{{ $aset->tgl_beli }}</td>
                                    <td>{{ $aset->jumlah }}</td>
                                    <td>{{ formatRupiah($aset->nilai) }}</td> <!-- Format nilai dengan formatRupiah -->
                                    <td>{{ $aset->wkt_ekonomis }}</td>
                                    <td>
                                        {{ $masa_pakai }}
                                    </td>
                                    <td>{{ formatRupiah($jumlah_penyusutan) }}</td>
                                    <td>{{ formatRupiah($saat_ini) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7">Akumulasi Penyusutan</td>
                                <td style="font-weight: bold; background-color:yellow">{{ formatRupiah($akumulasi) }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="8">Total Inventaris</td>
                                <td style="font-weight: bold; background-color:yellow">{{ formatRupiah($investasi) }}
                                </td>

                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
</body>
