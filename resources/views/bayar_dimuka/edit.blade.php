@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Edit Aset
                    </div>

                    <form action="/aset/bdmuk/{{ $aset->id }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Untuk mengindikasikan bahwa ini adalah permintaan update -->
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" required
                                value="{{ old('keterangan', $aset->keterangan) }}">
                            @error('keterangan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control rupiah" id="nilai" name="nilai"
                                onkeyup="onlyNumberAmount(this)" required
                                value="{{ old('nilai', formatNomor($aset->nilai)) }}">
                            @error('nilai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="wkt_ekonomis" class="form-label">Waktu Ekonomis (Tahun)</label>
                            <input type="number" class="form-control" id="wkt_ekonomis" name="wkt_ekonomis" required
                                value="{{ old('wkt_ekonomis', $aset->wkt_ekonomis) }}">
                            @error('wkt_ekonomis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="masa_pakai" class="form-label">Masa Pakai (Tahun)</label>
                            <input type="number" class="form-control" id="masa_pakai" name="masa_pakai"
                                value="{{ old('masa_pakai', $aset->masa_pakai) }}">
                            @error('masa_pakai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/aset/bdmuk" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
