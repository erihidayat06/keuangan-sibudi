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
                <h2>Rincian Laba Rugi Ditahan</h2>
                <h2>{{ unitUsaha()->nm_bumdes }}</h2>
            </div>

            <div class="card overflow-auto">
                <div class="card-body">


                    <table class="table table-striped table-hover datatable">
                        <thead>
                            <tr style="background-color: rgb(5, 177, 245)">
                                <th scope="col">#</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Hasil</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">PADES</th>
                                <th scope="col">Lainya</th>
                                <th scope="col">Akumulasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dithns as $dithn)
                                @php
                                    $pades = $dithn->nilai * ($dithn->pades / 100);
                                    $lainya = $dithn->nilai * ($dithn->lainya / 100);
                                    $akumulasi = $dithn->nilai * ($dithn->akumulasi / 100);
                                @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $dithn->tahun }}</td>
                                    <td>{{ $dithn->hasil }}</td>
                                    <td>{{ formatRupiah($dithn->nilai) }}</td>
                                    <td>{{ formatRupiah($pades) }}</td>
                                    <td>{{ formatRupiah($lainya) }}</td>
                                    <td>{{ formatRupiah($akumulasi) }}</td>

                                </tr>
                                <tr>
                                    <td colspan="6">Akumulasi</td>
                                    <td style="background-color: rgb(245, 201, 5)">{{ formatRupiah($total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>