@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Data Kas</h5>

                    <form action="{{ route('buk.update', $transaksi->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Tanggal Field -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                                name="tanggal" value="{{ old('tanggal', $transaksi->tanggal) }}"
                                min="{{ \Carbon\Carbon::now()->subYears(10)->format('Y-m-d') }}"
                                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transaksi Field -->
                        <div class="mb-3">
                            <label for="transaksi" class="form-label">Nama Transaksi</label>
                            <input type="text" class="form-control @error('transaksi') is-invalid @enderror"
                                id="transaksi" name="transaksi" value="{{ old('transaksi', $transaksi->transaksi) }}">
                            @error('transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Field (Masuk/Keluar) -->
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Masuk/Keluar</label>
                            <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
                                <option value="debit" {{ old('jenis', $transaksi->jenis) == 'debit' ? 'selected' : '' }}>
                                    Masuk</option>
                                <option value="kredit" {{ old('jenis', $transaksi->jenis) == 'kredit' ? 'selected' : '' }}>
                                    Keluar</option>
                                <option value="tetap" {{ old('jenis', $transaksi->jenis) == 'tetap' ? 'selected' : '' }}>
                                    Tetap</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Dana Field -->
                        <div class="mb-3">
                            <label for="jenis_dana" class="form-label">Jenis Dana (Arus Kas)</label>
                            <select class="form-select @error('jenis_dana') is-invalid @enderror" id="jenis_dana"
                                name="jenis_dana">
                                <option value="operasional"
                                    {{ old('jenis_dana', $transaksi->jenis_dana) == 'operasional' ? 'selected' : '' }}>
                                    Operasional</option>
                                <option value="iventasi"
                                    {{ old('jenis_dana', $transaksi->jenis_dana) == 'iventasi' ? 'selected' : '' }}>
                                    Investasi</option>
                                <option value="pendanaan"
                                    {{ old('jenis_dana', $transaksi->jenis_dana) == 'pendanaan' ? 'selected' : '' }}>
                                    Pendanaan</option>
                                <option value="tidak_dihitung"
                                    {{ old('jenis_dana', $transaksi->jenis_dana) == 'tidak_dihitung' ? 'selected' : '' }}>
                                    Tidak Dihitung (Transfer / Non-Arus Kas)</option>
                            </select>
                            @error('jenis_dana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nilai Field -->
                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" onkeyup="onlyNumberAmount(this)"
                                class="form-control @error('nilai') is-invalid @enderror" id="nilai" name="nilai"
                                value="{{ old('nilai', $transaksi->nilai) }}">
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis Laba Rugi Field -->
                        <div class="mb-3">
                            <label for="jenis_lr" class="form-label">Jenis Laba/rugi</label>
                            <select class="form-select @error('jenis_lr') is-invalid @enderror" id="jenis_lr"
                                name="jenis_lr">

                                <option value="kas"
                                    {{ old('jenis_lr', $transaksi->jenis_lr) == 'kas' ? 'selected' : '' }}>
                                    Kas
                                </option>

                                @foreach ($units as $unit)
                                    <option value="pu{{ $unit->kode }}"
                                        {{ old('jenis_lr', $transaksi->jenis_lr) == 'pu' . $unit->kode ? 'selected' : '' }}>
                                        Pendapatan {{ $unit->nm_unit }}
                                    </option>
                                @endforeach

                                @foreach ($units as $unit)
                                    <option value="bo{{ $unit->kode }}"
                                        {{ old('jenis_lr', $transaksi->jenis_lr) == 'bo' . $unit->kode ? 'selected' : '' }}>
                                        Biaya Operasional {{ $unit->nm_unit }}
                                    </option>
                                @endforeach

                                <option value="bno1"
                                    {{ old('jenis_lr', $transaksi->jenis_lr) == 'bno1' ? 'selected' : '' }}>
                                    Gaji Pengurus</option>
                                <option value="bno2"
                                    {{ old('jenis_lr', $transaksi->jenis_lr) == 'bno2' ? 'selected' : '' }}>
                                    ATK</option>
                                <option value="bno3"
                                    {{ old('jenis_lr', $transaksi->jenis_lr) == 'bno3' ? 'selected' : '' }}>
                                    Rapat-Rapat</option>
                                <option value="bno4"
                                    {{ old('jenis_lr', $transaksi->jenis_lr) == 'bno4' ? 'selected' : '' }}>
                                    Lain-lain</option>
                            </select>

                            @error('jenis_lr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
