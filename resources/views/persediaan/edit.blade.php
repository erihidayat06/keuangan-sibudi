@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Edit Data Barang
                    </div>

                    <form action="/aset/persediaan/{{ $barang->id }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Method untuk update --}}

                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" name="item" id="item"
                                class="form-control @error('item') is-invalid @enderror"
                                value="{{ old('item', $barang->item) }}">
                            @error('item')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" name="satuan" id="satuan"
                                class="form-control @error('satuan') is-invalid @enderror"
                                value="{{ old('satuan', $barang->satuan) }}">
                            @error('satuan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="hpp" class="form-label">HPP</label>
                            <input type="text" name="hpp" id="hpp"
                                class="form-control @error('hpp') is-invalid @enderror" onkeyup="onlyNumberAmount(this)"
                                value="{{ old('hpp', formatNomor($barang->hpp)) }}">
                            @error('hpp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nilai_jual" class="form-label">Nilai Jual</label>
                            <input type="text" name="nilai_jual" id="nilai_jual"
                                class="form-control @error('nilai_jual') is-invalid @enderror"
                                onkeyup="onlyNumberAmount(this)"
                                value="{{ old('nilai_jual', formatNomor($barang->nilai_jual)) }}">
                            @error('nilai_jual')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jml_awl" class="form-label">Jumlah Awal</label>
                            <input type="text" name="jml_awl" id="jml_awl"
                                class="form-control @error('jml_awl') is-invalid @enderror" onkeyup="onlyNumberAmount(this)"
                                value="{{ old('jml_awl', formatNomor($barang->jml_awl)) }}">
                            @error('jml_awl')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="masuk" class="form-label">Masuk</label>
                            <input type="text" name="masuk" id="masuk"
                                class="form-control @error('masuk') is-invalid @enderror" onkeyup="onlyNumberAmount(this)"
                                value="{{ old('masuk', formatNomor($barang->masuk)) }}">
                            @error('masuk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keluar" class="form-label">Keluar</label>
                            <input type="text" name="keluar" id="keluar"
                                class="form-control @error('keluar') is-invalid @enderror" onkeyup="onlyNumberAmount(this)"
                                value="{{ old('keluar', formatNomor($barang->keluar)) }}">
                            @error('keluar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
