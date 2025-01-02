@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="pagetitle text-center">
            <h1>APLIKASI PEMBUKUAN DAN KEUANGAN BUMDESA TERINTEGRASI</h1>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ID BUMDES</h5>

                    <form action="/{{ $profil->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Table for input fields -->
                        <table class="table table-bordered">
                            <tbody>
                                <!-- Row 1: Nama BUMDes -->
                                <tr>
                                    <td><label for="nm_bumdes">Nama BUMDes</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('nm_bumdes') is-invalid @enderror"
                                            id="nm_bumdes" name="nm_bumdes" value="{{ old('nm_bumdes', $profil) }}">
                                        @error('nm_bumdes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 2: Desa -->
                                <tr>
                                    <td><label for="desa">Desa</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('desa') is-invalid @enderror"
                                            id="desa" name="desa" value="{{ old('desa', $profil) }}">
                                        @error('desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 3: Kecamatan -->
                                <tr>
                                    <td><label for="kecamatan">Kecamatan</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('kecamatan') is-invalid @enderror"
                                            id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $profil) }}">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 4: Nama Direktur -->
                                <tr>
                                    <td><label for="nm_direktur">Nama Direktur</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_direktur') is-invalid @enderror" id="nm_direktur"
                                            name="nm_direktur" value="{{ old('nm_direktur', $profil) }}">
                                        @error('nm_direktur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 5: Nama Sekretaris -->
                                <tr>
                                    <td><label for="nm_serkertaris">Nama Sekretaris</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_serkertaris') is-invalid @enderror"
                                            id="nm_serkertaris" name="nm_serkertaris"
                                            value="{{ old('nm_serkertaris', $profil) }}">
                                        @error('nm_serkertaris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 6: Nama Bendahara -->
                                <tr>
                                    <td><label for="nm_bendahara">Nama Bendahara</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_bendahara') is-invalid @enderror"
                                            id="nm_bendahara" name="nm_bendahara"
                                            value="{{ old('nm_bendahara', $profil) }}">
                                        @error('nm_bendahara')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 7: Nama Pengawas -->
                                <tr>
                                    <td><label for="nm_pengawas">Nama Pengawas</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_pengawas') is-invalid @enderror" id="nm_pengawas"
                                            name="nm_pengawas" value="{{ old('nm_pengawas', $profil) }}">
                                        @error('nm_pengawas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 8: Nama Penasehat -->
                                <tr>
                                    <td><label for="nm_penasehat">Nama Penasehat</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_penasehat') is-invalid @enderror"
                                            id="nm_penasehat" name="nm_penasehat"
                                            value="{{ old('nm_penasehat', $profil) }}">
                                        @error('nm_penasehat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>


                                <!-- Row 17: No Badan -->
                                <tr>
                                    <td><label for="no_badan">No Badan</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_badan') is-invalid @enderror"
                                            id="no_badan" name="no_badan" value="{{ old('no_badan', $profil) }}">
                                        @error('no_badan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 18: No Perdes -->
                                <tr>
                                    <td><label for="no_perdes">No Perdes</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_perdes') is-invalid @enderror"
                                            id="no_perdes" name="no_perdes" value="{{ old('no_perdes', $profil) }}">
                                        @error('no_perdes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 19: No SK -->
                                <tr>
                                    <td><label for="no_sk">No SK</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_sk') is-invalid @enderror"
                                            id="no_sk" name="no_sk" value="{{ old('no_sk', $profil) }}">
                                        @error('no_sk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <!-- Row 19: No NIB -->
                                <tr>
                                    <td><label for="no_nib">No ijin Berusaha (NIB)</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_nib') is-invalid @enderror"
                                            id="no_nib" name="no_nib" value="{{ old('no_nib', $profil) }}">
                                        @error('no_nib')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
