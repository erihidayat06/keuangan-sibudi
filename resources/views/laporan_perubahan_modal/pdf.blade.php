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

<body>
    <div class="card">
        <div class="card-body">
            <div class="card-title">4. LAPORAN PERUBAHAN MODAL</div>


            <!-- Pendapatan Section -->
            <div class="report-section">

                <table class="table-report">
                    <tr>
                        <td colspan="2">Penyertaan modal desa</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($modal_desa) }}</td>
                    </tr>
                    <tr class="">
                        <td colspan="2">Penyertaan modal masyarakat</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($modal_masyarakat) }}</td>
                    </tr>



                    <tr>
                        <td colspan="2">Laba/Rugi ditahan</td>
                        <td class="text-end"></td>
                        <td class="text-end"></td>

                    </tr>

                    <tr>
                        <td>Laba Berjalan</td>
                        <td class="text-end d-flex  justify-content-end">{{ old('tahun', $ekuitas) }}</td>
                        <td class="text-end red-text">{{ formatRupiah($laba_berjalan) }}</td>

                    </tr>
                    @php
                        $tambah = 0;
                        $pades = 0;
                        $lainya = 0;
                        if (isset($ekuitas->akumulasi) and isset($ekuitas->pades) and isset($ekuitas->lainya)) {
                            $tambah = $laba_berjalan * ($ekuitas->akumulasi / 100);
                            $pades = $laba_berjalan * ($ekuitas->pades / 100);
                            $lainya = $laba_berjalan * ($ekuitas->lainya / 100);
                        }
                        $modal_akhir = $tambah + $modal_desa + $modal_masyarakat;
                    @endphp
                    <tr class="">

                        <td>
                            <span class="ms-5"> Tambah Modal</span>
                        </td>
                        <td class="text-end d-flex  justify-content-end">{{ old('akumulasi', $ekuitas->akumulasi) }}%
                        </td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($tambah) }}</td>
                    </tr>
                    <tr>
                        <td> <span class="ms-5">PADes</span></td>
                        <td class="text-end d-flex  justify-content-end">{{ old('pades', $ekuitas->pades) }}%
                        </td>
                        <td class="text-end red-text">{{ formatRupiah($pades) }}</td>
                        <td class="text-end"></td>

                    </tr>
                    <tr>
                        <td> <span class="ms-5">Lain Lain</span></td>
                        <td class="text-end d-flex  justify-content-end">{{ old('lainya', $ekuitas->lainya) }}%
                        </td>
                        <td class="text-end red-text">{{ formatRupiah($lainya) }} </td>
                        <td class="text-end"></td>

                    </tr>
                    <tr>
                        <td>Modal Akhir 1 Januari</td>
                        <td class=""> {{ $ekuitas->tahun + 1 }}</td>
                        <td class="text-end"></td>
                        <td class="text-end red-text">{{ formatRupiah($modal_akhir) }}</td>
                    </tr>

                </table>

            </div>
        </div>
    </div>
</body>