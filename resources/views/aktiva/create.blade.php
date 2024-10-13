@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Tambah Aset
                    </div>

                    <form action="/aset/aktivalain" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis aktiva</label>
                            <input type="text" class="form-control" id="jenis" name="jenis" required
                                value="{{ old('jenis') }}">
                            @error('jenis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai" class="form-label">Nilai</label>
                            <input type="text" class="form-control rupiah" id="nilai" name="nilai"
                                onkeyup="onlyNumberAmount(this)" required value="{{ old('nilai') }}">
                            @error('nilai')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="wkt_ekonomis" class="form-label">Waktu Ekonomis (Tahun)</label>
                            <input type="number" class="form-control" id="wkt_ekonomis" name="wkt_ekonomis" required
                                value="{{ old('wkt_ekonomis') }}">
                            @error('wkt_ekonomis')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="/aset/aktivalain" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
