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
    }
</style>

<body>
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h2>Rincian Aset Iventari</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
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

                                        {{ $aset->masa_pakai == null ? 0 : $aset->masa_pakai }}

                                    </td>
                                    <td>{{ formatRupiah($penyusutan) }}</td>
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