<p>G. Rencana Penambahan Modal</p>
<div class="isi">
    <p>1. Deskripsi Unit Usaha yang akan dikembangkan</p>
    <div class="isi">
        <p>
            Nama unit/keterangan : {{ $proker->unit_usaha }}
        </p>

        <p>
            Status unit usaha : {{ $proker->status_unit }}
        </p>
    </div>
    <p>2. Nilai Pengajuan Penambahan Penyertaan Modal</p>
    <div class="isi">
        <p>
            Nilai : {{ formatRupiah($proker->jumlah) }}
        </p>
        <p>
            Rincian Penggunaan Modal:
        </p>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Jenis Biaya</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($alokasis as $alokasi)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $alokasi->item }}</td>
                        <td>{{ $alokasi->jenis_biaya }}</td>
                        <td>{{ formatRupiah($alokasi->nilai) }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <p>3. Analisa Kelayakan Usaha</p>
    <div class="isi">
        <p>
            a. Aspek pasar dan pemasaran
        </p>
        <div class="isi">
            <p>{{ $proker->aspek_pasar }}</p>
        </div>
        <p>
            b. Aspek Keuangan
        </p>
        <div class="isi">
            <p>{{ $proker->aspek_keuangan }}</p>
        </div>
        <p>
            c. Aspek Teknis
        </p>
        <div class="isi">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Resiko</th>
                        <th>Penyebab</th>
                        <th>Antisipasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    <!-- Contoh Data -->
                    @foreach ($rasios as $rasio)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $rasio->resiko }}</td>
                            <td>{{ $rasio->penyebab }}</td>
                            <td>{{ $rasio->antisipasi }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p>
            d. Aspek lainnya
        </p>
        <div class="isi">
            <p>{{ $proker->aspek_lainya }}</p>

        </div>

        <p>4. Strategi Pemasaran</p>
        <div class="isi">
            <p>
                {{ $proker->strategi_pemasaran }}
            </p>
        </div>

        <p>5. Kesimpulan tentang usaha yang akan dirintis/dikembangkan</p>
        <div class="isi">
            <p>
                {{ $proker->kesimpulan }}
            </p>
        </div>
    </div>
