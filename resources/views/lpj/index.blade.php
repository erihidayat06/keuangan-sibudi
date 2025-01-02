@extends('layouts.main')

@section('container')
    <div class="card">
        <div class="card-body">
            <form action="/lpj/{{ $lpj->id }}" method="POST">
                @csrf
                @method('PUT') <!-- Required for updating data -->

                <h3 class="card-title">Ikhtisar</h3>

                <div class="mb-3">
                    <label for="kegiatan_usaha" class="form-label">1. Jalannya kegiatan usaha sesuai dengan rencana program
                        kerja?</label>
                    <div>
                        <input type="radio" name="kegiatan_usaha" value="Sesuai" id="sesuai" class="form-check-input"
                            {{ $lpj->kegiatan_usaha == 'Sesuai' ? 'checked' : '' }} required>
                        <label for="sesuai" class="form-check-label">Sesuai</label>
                    </div>
                    <div>
                        <input type="radio" name="kegiatan_usaha" value="Tidak Sesuai" id="tidak_sesuai"
                            class="form-check-input" {{ $lpj->kegiatan_usaha == 'Tidak Sesuai' ? 'checked' : '' }}>
                        <label for="tidak_sesuai" class="form-check-label">Tidak Sesuai</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="penambahan_modal" class="form-label">2. Penambahan penyertaan modal di tahun
                        pembukuan?</label>
                    <div>
                        <input type="radio" name="penambahan_modal" value="Ada" id="ada" class="form-check-input"
                            {{ $lpj->penambahan_modal == 'Ada' ? 'checked' : '' }} required>
                        <label for="ada" class="form-check-label">Ada</label>
                    </div>
                    <div>
                        <input type="radio" name="penambahan_modal" value="Tidak Ada" id="tidak_ada"
                            class="form-check-input" {{ $lpj->penambahan_modal == 'Tidak Ada' ? 'checked' : '' }}>
                        <label for="tidak_ada" class="form-check-label">Tidak Ada</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="hasil_capaian" class="form-label">3. Gambarkan hasil capaian selama satu tahun!</label>
                    <textarea name="hasil_capaian" id="hasil_capaian" rows="3" class="form-control">{{ $lpj->hasil_capaian }}</textarea>
                </div>

                <h3 class="card-title">Laporan Direktur</h3>

                <div class="mb-3">
                    <label for="kebijakan_strategi" class="form-label">4. Kebijakan dan strategi yang telah dilakukan
                        manajemen</label>
                    <textarea name="kebijakan_strategi" id="kebijakan_strategi" rows="3" class="form-control" required>{{ $lpj->kebijakan_strategi }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="tantangan_hambatan" class="form-label">5. Tantangan dan hambatan yang dihadapi</label>
                    <textarea name="tantangan_hambatan" id="tantangan_hambatan" rows="3" class="form-control" required>{{ $lpj->tantangan_hambatan }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="apresiasi" class="form-label">6. Penyampaian apresiasi</label>
                    <textarea name="apresiasi" id="apresiasi" rows="3" class="form-control" required>{{ $lpj->apresiasi }}</textarea>
                </div>

                <h3 class="card-title">Laporan Pengawas</h3>

                <div class="mb-3">
                    <label for="tugas_pengawasan" class="form-label">7. Tugas Pengawasan yang telah dilakukan</label>
                    <textarea name="tugas_pengawasan" id="tugas_pengawasan" rows="3" class="form-control" required>{{ $lpj->tugas_pengawasan }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="pandangan_pengawas" class="form-label">8. Pandangan Pengawas atas realisasi program
                        kerja</label>
                    <textarea name="pandangan_pengawas" id="pandangan_pengawas" rows="3" class="form-control" required>{{ $lpj->pandangan_pengawas }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="catatan_pengawas" class="form-label">9. Catatan dari pengawas</label>
                    <textarea name="catatan_pengawas" id="catatan_pengawas" rows="3" class="form-control" required>{{ $lpj->catatan_pengawas }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="rekomendasi_pengawas" class="form-label">10. Rekomendasi pengawas</label>
                    <textarea name="rekomendasi_pengawas" id="rekomendasi_pengawas" rows="3" class="form-control" required>{{ $lpj->rekomendasi_pengawas }}</textarea>
                </div>

                <h3 class="card-title">Laporan Kinerja</h3>

                <div class="mb-3">
                    <label for="hasil_kinerja" class="form-label">11. Interpretasikan hasil Kinerja masing-masing unit
                        usaha</label>
                    <textarea name="hasil_kinerja" id="hasil_kinerja" rows="3" class="form-control" required>{{ $lpj->hasil_kinerja }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="permasalahan_usaha" class="form-label">12. Permasalahan yang mempengaruhi usaha</label>
                    <textarea name="permasalahan_usaha" id="permasalahan_usaha" rows="3" class="form-control" required>{{ $lpj->permasalahan_usaha }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="potensi_peluang" class="form-label">13. Potensi, Peluang dan Prospek Usaha</label>
                    <textarea name="potensi_peluang" id="potensi_peluang" rows="3" class="form-control" required>{{ $lpj->potensi_peluang }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="strategi_kebijakan" class="form-label">14. Strategi dan Kebijakan tahun berikutnya</label>
                    <textarea name="strategi_kebijakan" id="strategi_kebijakan" rows="3" class="form-control" required>{{ $lpj->strategi_kebijakan }}</textarea>
                </div>
                <style>
                    .selanjutnya {
                        width: 100%;
                        padding: 15px;
                        border-radius: 10px;
                        position: sticky;
                        bottom: 0;
                        background-color: white;
                    }

                    .back-to-top {
                        display: none !important;
                    }
                </style>




                <div class="selanjutnya text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a target="_blank" href="/cetak/lpj" class="btn btn-danger">Cetak</a>
                </div>

            </form>
        </div>
    </div>
@endsection
