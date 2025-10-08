@extends('layouts.main')

@section('container')
    <div class="card mb-3 col-lg-8">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Buat Akun</h5>
                <p class="text-center small">Masukkan detail pribadi Anda untuk membuat akun</p>
            </div>

            <form method="POST" action="/admin/data-user/store">
                @csrf
                <div class="col-12 mt-3">
                    <label for="yourName" class="form-label">Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="yourEmail" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="col-12 mt-3">
                    <label for="no_wa" class="form-label">Nomor WA</label>
                    <input id="no_wa" type="no_wa" class="form-control @error('no_wa') is-invalid @enderror"
                        name="no_wa" value="{{ old('no_wa') }}" required autocomplete="no_wa">
                    @error('no_wa')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="yourPassword" class="form-label">Konfirmasi Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                </div>

                <!-- 🔽 Tambahan Wilayah -->
                <div class="col-12 mt-3">
                    <label for="kabupaten" class="form-label">Kabupaten (Jawa Tengah)</label>
                    <select id="kabupaten" name="kabupaten" class="form-select @error('kabupaten') is-invalid @enderror"
                        required>
                        <option value="">-- Pilih Kabupaten --</option>
                    </select>
                    @error('kabupaten')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <select id="kecamatan" name="kecamatan" class="form-select @error('kecamatan') is-invalid @enderror"
                        required>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                    @error('kecamatan')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="desa" class="form-label">Desa</label>
                    <select id="desa" name="desa" class="form-select @error('desa') is-invalid @enderror" required>
                        <option value="">-- Pilih Desa --</option>
                    </select>
                    @error('desa')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <!-- 🔼 End Wilayah -->

                <div class="col-12 mt-3">
                    <label for="referral" class="form-label">Pilih BUMDesa</label>
                    <select class="form-select @error('referral') is-invalid @enderror" id="referral" name="referral">
                        <option value="1" {{ old('referral') == '1' ? 'selected' : '' }}>BUMDesa</option>
                        <option value="0" {{ old('referral') == '0' ? 'selected' : '' }}>BUMDesa Bersama</option>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <label for="langganan" class="form-label">Pilih Langganan</label>
                    <select class="form-select @error('langganan') is-invalid @enderror" id="langganan" name="langganan">
                        <option value="0" {{ old('langganan') == 0 ? 'selected' : '' }}>
                            Akun Baru
                        </option>
                        <?php for ($i = 1; $i <= 50; $i++): ?>
                        <option value="{{ $i }}" {{ old('langganan') == $i ? 'selected' : '' }}>
                            {{ $i }} Bulan
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                </div>
            </form>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const kabupatenSelect = document.getElementById("kabupaten");
                    const kecamatanSelect = document.getElementById("kecamatan");
                    const desaSelect = document.getElementById("desa");

                    // --- Ambil daftar kabupaten di Jawa Tengah (ID: 33) ---
                    fetch("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/33.json")
                        .then(res => res.json())
                        .then(data => {
                            data.forEach(kab => {
                                const opt = document.createElement("option");
                                opt.value = kab.name; // kirim nama ke backend
                                opt.dataset.id = kab.id; // simpan ID untuk fetch kecamatan
                                opt.textContent = kab.name;
                                kabupatenSelect.appendChild(opt);
                            });
                        });

                    kabupatenSelect.addEventListener("change", function() {
                        const kabupatenId = this.options[this.selectedIndex].dataset.id;
                        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                        if (!kabupatenId) return;

                        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`)
                            .then(res => res.json())
                            .then(data => {
                                data.forEach(kec => {
                                    const opt = document.createElement("option");
                                    opt.value = kec.name;
                                    opt.dataset.id = kec.id;
                                    opt.textContent = kec.name;
                                    kecamatanSelect.appendChild(opt);
                                });
                            })
                            .catch(() => alert("❌ Gagal memuat kecamatan."));
                    });

                    kecamatanSelect.addEventListener("change", function() {
                        const kecamatanId = this.options[this.selectedIndex].dataset.id;
                        desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
                        if (!kecamatanId) return;

                        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`)
                            .then(res => res.json())
                            .then(data => {
                                data.forEach(desa => {
                                    const opt = document.createElement("option");
                                    opt.value = desa.name;
                                    opt.textContent = desa.name;
                                    desaSelect.appendChild(opt);
                                });
                            })
                            .catch(() => alert("❌ Gagal memuat desa."));
                    });

                    // --- Tampilkan modal hanya jika data belum lengkap ---
                    @if (empty($profil->kabupaten) || empty($profil->kecamatan) || empty($profil->desa) || empty($profil->no_wa))
                        const lokasiModal = new bootstrap.Modal(document.getElementById('lokasiModal'), {
                            backdrop: 'static',
                            keyboard: false
                        });
                        lokasiModal.show();
                    @endif
                });
            </script>



        </div>
    </div>
@endsection
