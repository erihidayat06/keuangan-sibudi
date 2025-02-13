@extends('layouts_proker.main')

@section('container')
    <!-- Alokasi -->
    <div class="modal fade" id="alokasiModal" tabindex="-1" aria-labelledby="alokasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="alokasiModalLabel">Tambah Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/proker/alokasi/store" method="POST">
                        @csrf
                        <table class="table table-bordered" id="data-table">
                            <thead>
                                <tr>

                                    <th scope="col">Item</th>
                                    <th scope="col">Jenis Biaya<br>(Investasi/Operasional)</th>
                                    <th scope="col">Nilai</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>

                                    <td>
                                        <input type="text" class="form-control" name="data[0][item]" placeholder="Item">
                                    </td>
                                    <td>
                                        <select class="form-control" name="data[0][jenis_biaya]">
                                            <option value="Investasi">Investasi</option>
                                            <option value="Operasional">Operasional</option>
                                            <option value="Variabel">Variabel</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="data[0][nilai]"
                                            placeholder="Nilai">
                                    </td>
                                    <td>
                                        <button type="button" id="addRow" class="btn btn-sm btn-success">Tambah</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <script>
                            let rowCount = 1;

                            document.getElementById('addRow').addEventListener('click', function() {
                                const table = document.getElementById('data-table').getElementsByTagName('tbody')[0];
                                const newRow = document.createElement('tr');

                                newRow.innerHTML = `
                                <td>
                                    <input type="text" class="form-control" name="data[${rowCount}][item]" placeholder="Item">
                                </td>
                                <td>
                                    <select class="form-control" name="data[${rowCount}][jenis_biaya]">
                                        <option value="Investasi">Investasi</option>
                                        <option value="Operasional">Operasional</option>
                                         <option value="Variabel">Variabel</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="data[${rowCount}][nilai]" placeholder="Nilai">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger removeRow">Hapus</button>
                                </td>
                            `;

                                table.appendChild(newRow);
                                rowCount++;
                            });

                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.classList.contains('removeRow')) {
                                    e.target.closest('tr').remove();
                                }
                            });
                        </script>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Resiko Modal -->
    <div class="modal fade" id="resikoModal" tabindex="-1" aria-labelledby="resikoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="resikoModalLabel">Tambah Resiko Jalannya Usaha</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/proker/resiko/store" method="POST">
                        @csrf
                        <table class="table table-bordered" id="resikoTable">
                            <thead>
                                <tr>
                                    <th>Resiko Jalannya Usaha</th>
                                    <th>Penyebab</th>
                                    <th>Antisipasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="data[0][resiko]"
                                            placeholder="Resiko">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="data[0][penyebab]"
                                            placeholder="Penyebab">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="data[0][antisipasi]"
                                            placeholder="Antisipasi">
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-sm btn-success"
                                            id="tambahResiko">Tambah</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <script>
                            let resikoIndex = 0;

                            document.getElementById('tambahResiko').addEventListener('click', function() {
                                resikoIndex++;

                                const tableBody = document.querySelector('#resikoTable tbody');
                                const newRow = `
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="data[${resikoIndex}][resiko]" placeholder="Resiko">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="data[${resikoIndex}][penyebab]" placeholder="Penyebab">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="data[${resikoIndex}][antisipasi]" placeholder="Antisipasi">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger hapusResiko">Hapus</button>
                                    </td>
                                </tr>
                            `;

                                tableBody.insertAdjacentHTML('beforeend', newRow);
                            });

                            document.addEventListener('click', function(e) {
                                if (e.target && e.target.classList.contains('hapusResiko')) {
                                    e.target.closest('tr').remove();
                                }
                            });
                        </script>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>




    <p>Apakah ada Rencana Pengajuan Penambahan Penyertaan Modal di tahun berikutnya</p>
    <div class="form-check">
        <input type="hidden" id="proker-id" value="{{ $proker->id }}">
        <input class="form-check-input" type="radio" value="Ada" name="status" id="flexRadioDefault1" data-id="1"
            {{ $proker->status == 'Ada' ? 'checked' : '' }}>
        <label class="form-check-label" for="flexRadioDefault1">
            Ada
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" value="Tidak Ada" name="status" id="flexRadioDefault2"
            {{ $proker->status == 'Tidak Ada' ? 'checked' : '' }} data-id="1">
        <label class="form-check-label" for="flexRadioDefault2">
            Tidak Ada
        </label>
    </div>


    <script>
        $(document).ready(function() {
            // Ketika memilih radio button status
            $('input[name="status"]').on('change', function() {
                var newStatus = $(this).val(); // Ambil nilai status baru
                var id = $(this).data('id'); // Ambil ID yang terkait dengan status

                var proker_id = $('#proker-id').val(); // Ambil nilai status baru
                // Perbarui status di tabel
                $('#status-' + id).text(newStatus);

                // Kirimkan request AJAX untuk memperbarui status di database
                $.ajax({
                    url: '/update-status/' + proker_id, // URL endpoint untuk memperbarui status
                    method: 'POST',
                    data: {
                        id: id,
                        status: newStatus,
                        _token: '{{ csrf_token() }}' // Kirim token CSRF untuk Laravel
                    },
                    success: function(response) {
                        console.log('Status berhasil diperbarui');
                    },
                    error: function(error) {
                        console.log('Gagal memperbarui status');
                    }
                });
            });
        });
    </script>

    <form action="/proker/penambahan/modal/{{ $proker->id }}" method="POST">
        @csrf <!-- Tambahkan CSRF token untuk keamanan -->
        @method('PUT')
        <div class="lengkapi" style="display: none;">
            <p>Jika ada, silahkan lengkapi data di bawah ini </p>
            <br>
            <label class="mt-3" for="unit">1. Penggunaan Penyertaan Modal untuk unit usaha apa</label>
            <div class="ms-4">
                <input type="text" class="form-control" id="unit" value="{{ old('unit_usaha', $proker) }}"
                    name="unit_usaha">
            </div>

            <label class="mt-3" for="status_unit">2. Status unit usaha yang akan diberikan penambahan penyertaan
                modal</label>
            <div class="ms-4">
                <div class="form-check">
                    <input class="form-check-input" value="Baru/ Rintisan" type="radio" name="status_unit"
                        id="Baru/ Rintisan2"
                        {{ old('status_unit', $proker->status_unit) == 'Baru/ Rintisan' ? 'checked' : '' }}>
                    <label class="form-check-label" for="Baru/ Rintisan2">
                        Baru/ Rintisan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="Pengembangan" type="radio" name="status_unit"
                        id="Pengembangan2"
                        {{ old('status_unit', $proker->status_unit) == 'Pengembangan' ? 'checked' : '' }}>
                    <label class="form-check-label" for="Pengembangan2">
                        Pengembangan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="Penyehatan" type="radio" name="status_unit"
                        id="Penyehatan2" {{ old('status_unit', $proker->status_unit) == 'Penyehatan' ? 'checked' : '' }}>
                    <label class="form-check-label" for="Penyehatan2">
                        Penyehatan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="Lainnya" type="radio" name="status_unit" id="Lainnya2"
                        {{ old('status_unit', $proker->status_unit) == 'Lainnya' ? 'checked' : '' }}>
                    <label class="form-check-label" for="Lainnya2">
                        Lainnya
                    </label>
                </div>
            </div>


            <label class="mt-3" for="jumlah">3. Berapa jumlah penyertaan modal yang ingin diajukan</label>
            <div class="ms-4">
                <input type="text" class="form-control" id="jumlah" value="{{ old('jumlah', $proker) }}"
                    name="jumlah">
            </div>


            <label class="mt-3" for="aspek_pasar">4. Bagaimana analisa aspek pasar pada usaha tersebut</label>
            <div class="ms-4">
                <textarea class="form-control" id="aspek_pasar" name="aspek_pasar" rows="3">{{ old('aspek_pasar', $proker) }}</textarea>
            </div>

            <label class="mt-3" for="aspek_keuangan">5. Bagaimana analisa aspek keuangan pada pengembangan usaha
                tersebut</label>
            <style>
                .dotted-input {
                    border: none;
                    border-bottom: 1px dotted #000;
                    outline: none;
                    width: 100%;
                    background: transparent;
                }
            </style>
            <div class="ms-4">
                @php
                    // Decode JSON menjadi array, jika null, set array kosong
                    $aspek_keuangan = json_decode($proker->aspek_keuangan, true) ?? [];

                    // Pastikan array tidak kosong sebelum mengakses indeks pertama
                    $data_keuangan = $aspek_keuangan[0] ?? [];
                @endphp

                <table class="table-border mt-3">
                    <tr>
                        <td>Pendapatan Unit dalam setahun</td>
                        <td><span class="ms-4">Rp</span></td>
                        <td>
                            <input type="text" name="aspek_keuangan[0][pendapatan]"
                                value="{{ old('aspek_keuangan.0.pendapatan', $data_keuangan['pendapatan'] ?? '') }}"
                                class="dotted-input">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Biaya operasional dalam setahun</td>
                        <td><span class="ms-4">Rp</span></td>
                        <td>
                            <input type="text" name="aspek_keuangan[0][biaya]"
                                value="{{ old('aspek_keuangan.0.biaya', $data_keuangan['biaya'] ?? '') }}"
                                class="dotted-input">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Laba diperoleh dalam satu tahun</td>
                        <td><span class="ms-4">Rp</span></td>
                        <td>
                            <input type="text" name="aspek_keuangan[0][laba]"
                                value="{{ old('aspek_keuangan.0.laba', $data_keuangan['laba'] ?? '') }}"
                                class="dotted-input">
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pengembalian Modal (ROI)</td>
                        <td><span class="ms-4">Rp</span></td>
                        <td>
                            <input type="text" name="aspek_keuangan[0][pengembalian]"
                                value="{{ old('aspek_keuangan.0.pengembalian', $data_keuangan['pengembalian'] ?? '') }}"
                                class="dotted-input">
                        </td>
                        <td>Tahun</td>
                    </tr>
                </table>


                <p class="mt-3">Rincian lainya:</p>
                <textarea class="form-control" id="aspek_keuangan" name="aspek_keuangan[0][rincian]" rows="3">{{ old('aspek_keuangan.0.rincian', $data_keuangan['rincian'] ?? '') }}</textarea>
            </div>



            <label class="mt-3" for="aspek_lainya">6. Bagaimana analisa aspek lainnya (hukum, Lingkungan, sosial,
                politik, dll) pada
                pengembangan usaha tersebut</label>
            <div class="ms-4">
                <textarea class="form-control" id="aspek_lainya" name="aspek_lainya" rows="3">{{ old('aspek_lainya', $proker) }}</textarea>
            </div>

            <label class="mt-3" for="strategi_pemasaran">7. Bagaimana strategi pemasaran yang akan dilakukan</label>
            <div class="ms-4">
                <textarea class="form-control" id="strategi_pemasaran" name="strategi_pemasaran" rows="3">{{ old('strategi_pemasaran', $proker) }}</textarea>
            </div>

            <label class="mt-3" for="kesimpulan">8. Kesimpulan tentang rencana penambahan modal</label>
            <div class="ms-4">
                <textarea class="form-control" id="kesimpulan" name="kesimpulan" rows="3">{{ old('kesimpulan', $proker) }}</textarea>
            </div>


            <button type="submit" class="btn  btn-primary mt-5 ms-3">Simpan</button>
    </form>
    <br>


    <label class="mt-5" for="alokasi">9. Bagaimana alokasi penggunaan penyertaan modal</label>
    <br>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary mb-3 mt-3" data-bs-toggle="modal" data-bs-target="#alokasiModal">
        Tambah Data
    </button>
    <table class="table table-bordered ">
        <thead class="table-light">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Item</th>
                <th scope="col">Jenis Biaya<br>(Investasi/Operasional)</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            <!-- Contoh Data -->
            @foreach ($alokasis as $alokasi)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $alokasi->item }}</td>
                    <td>{{ $alokasi->jenis_biaya }}</td>
                    <td>{{ $alokasi->nilai }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <label class="mt-3" for="teknis">10. Bagaimana analisa aspek teknis pada pengembangan usaha tersebut
    </label><br>
    <!-- Button trigger modal -->
    <button type="button" id="teknis" class="btn btn-primary mb-3 mt-3" data-bs-toggle="modal"
        data-bs-target="#resikoModal">
        Tambah Resiko Usaha
    </button>
    <table class="table table-bordered ">
        <thead class="table">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Resiko Jalannya Usaha</th>
                <th scope="col">Penyebab</th>
                <th scope="col">Antisipasi</th>
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

    <script>
        // Ambil elemen radio buttons dan elemen .lengkapi
        const radioAda = document.getElementById('flexRadioDefault1');
        const radioTidakAda = document.getElementById('flexRadioDefault2');
        const lengkapi = document.querySelector('.lengkapi');

        // Fungsi untuk toggle elemen .lengkapi
        function toggleLengkapi() {
            if (radioAda.checked) {
                lengkapi.style.display = 'block'; // Tampilkan jika "Ada" dipilih
            } else {
                lengkapi.style.display = 'none'; // Sembunyikan jika "Tidak Ada" dipilih
            }
        }

        // Pasang event listener pada radio buttons
        radioAda.addEventListener('change', toggleLengkapi);
        radioTidakAda.addEventListener('change', toggleLengkapi);

        // Jalankan fungsi saat halaman dimuat untuk memastikan state awal
        document.addEventListener('DOMContentLoaded', toggleLengkapi);
    </script>
@endsection
