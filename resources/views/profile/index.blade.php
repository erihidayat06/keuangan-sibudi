@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="pagetitle text-center">
            <h1>APLIKASI PEMBUKUAN DAN KEUANGAN BUMDESA TERINTEGRASI</h1>
            <h1>KABUPATEN BREBES</h1>
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
                                    <td><label for="nm_sekertaris">Nama Sekretaris</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_sekertaris') is-invalid @enderror"
                                            id="nm_sekertaris" name="nm_sekertaris"
                                            value="{{ old('nm_sekertaris', $profil) }}">
                                        @error('nm_sekertaris')
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

                                <!-- Row 9: Unit Usaha 1 -->
                                <tr>
                                    <td><label for="unt_usaha1">Unit Usaha 1</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('unt_usaha1') is-invalid @enderror"
                                            id="unt_usaha1" name="unt_usaha1" value="{{ old('unt_usaha1', $profil) }}"
                                            placeholder="Unit usaha persediaan">
                                        @error('unt_usaha1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 10: Nama Kepala Unit 1 -->
                                <tr>
                                    <td><label for="nm_kepyun1">Nama Kepala Unit 1</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('nm_kepyun1') is-invalid @enderror"
                                            id="nm_kepyun1" name="nm_kepyun1" value="{{ old('nm_kepyun1', $profil) }}">
                                        @error('nm_kepyun1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 11: Unit Usaha 2 -->
                                <tr>
                                    <td><label for="unt_usaha2">Unit Usaha 2</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('unt_usaha2') is-invalid @enderror" id="unt_usaha2"
                                            name="unt_usaha2" value="{{ old('unt_usaha2', $profil) }}"
                                            placeholder="Unit usaha simpan pinjam">
                                        @error('unt_usaha2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 12: Nama Kepala Unit 2 -->
                                <tr>
                                    <td><label for="nm_kepyun2">Nama Kepala Unit 2</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_kepyun2') is-invalid @enderror" id="nm_kepyun2"
                                            name="nm_kepyun2" value="{{ old('nm_kepyun2', $profil) }}">
                                        @error('nm_kepyun2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 13: Unit Usaha 3 -->
                                <tr>
                                    <td><label for="unt_usaha3">Unit Usaha 3</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('unt_usaha3') is-invalid @enderror" id="unt_usaha3"
                                            name="unt_usaha3" value="{{ old('unt_usaha3', $profil) }}"
                                            placeholder="Unit usaha lainya">
                                        @error('unt_usaha3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 14: Nama Kepala Unit 3 -->
                                <tr>
                                    <td><label for="nm_kepyun3">Nama Kepala Unit 3</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_kepyun3') is-invalid @enderror"
                                            id="nm_kepyun3" name="nm_kepyun3" value="{{ old('nm_kepyun3', $profil) }}">
                                        @error('nm_kepyun3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 15: Unit Usaha 4 -->
                                <tr>
                                    <td><label for="unt_usaha4">Unit Usaha 4</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('unt_usaha4') is-invalid @enderror"
                                            id="unt_usaha4" name="unt_usaha4" value="{{ old('unt_usaha4', $profil) }}"
                                            placeholder="Unit usaha lainya">
                                        @error('unt_usaha4')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 16: Nama Kepala Unit 4 -->
                                <tr>
                                    <td><label for="nm_kepyun4">Nama Kepala Unit 4</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_kepyun4') is-invalid @enderror"
                                            id="nm_kepyun4" name="nm_kepyun4" value="{{ old('nm_kepyun4', $profil) }}">
                                        @error('nm_kepyun4')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 17: No Badan -->
                                <tr>
                                    <td><label for="no_badan">No Badan</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('no_badan') is-invalid @enderror" id="no_badan"
                                            name="no_badan" value="{{ old('no_badan', $profil) }}">
                                        @error('no_badan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 18: No Perdes -->
                                <tr>
                                    <td><label for="no_perdes">No Perdes</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('no_perdes') is-invalid @enderror" id="no_perdes"
                                            name="no_perdes" value="{{ old('no_perdes', $profil) }}">
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
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
