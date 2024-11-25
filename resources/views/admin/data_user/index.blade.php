@extends('layouts.main')



@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data User</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body mt-5">

                    <a href="/admin/data-user/create" class="btn btn-sm btn-success">Tambah User</a>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Sisa Langganan (Hari)</th>
                                <th scope="col">Status</th>
                                <th scope="col">password</th>
                                <th scope="col">Langganan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;

                            @endphp
                            @foreach ($users as $user)
                                @if ($user->role != 'admin')
                                    @php
                                        // Tanggal tujuan
                                        $targetDate = new DateTime($user->tgl_langganan);
                                        // Tanggal hari ini
                                        $today = new DateTime('now');

                                        // Hitung selisihnya
                                        $interval = $today->diff($targetDate);

                                        // Jika tanggal tujuan kurang dari hari ini, atur selisih ke 0
                                        $remainingDays = $targetDate < $today ? 0 : $interval->days;
                                    @endphp

                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $remainingDays }}

                                        </td>
                                        <td>
                                            @if ($user->status == 0)
                                                <p class="text-danger">Tidak Aktif</p>
                                            @else
                                                <p class="text-success">Aktif</p>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Button trigger modal -->
                                            <a href="" data-bs-toggle="modal"
                                                data-bs-target="#user{{ $user->id }}">
                                                Ubah Password
                                            </a>


                                        </td>
                                        <td>
                                            <!-- Button trigger modal -->
                                            <a href="" data-bs-toggle="modal"
                                                data-bs-target="#langganan{{ $user->id }}">
                                                Ubah Langganan
                                            </a>
                                        </td>
                                        <td>
                                            <form action="/admin/data-user/{{ $user->id }}" class="ms-2"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin di Hapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="langganan{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="langganan{{ $user->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="langganan{{ $user->id }}Label">Update
                                                    Langganan
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="/admin/langganan/{{ $user->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label for="langganan">Langganan {{ $user->id }}</label>
                                                    <select class="form-select" aria-label="Default select example"
                                                        name="langganan">
                                                        @php

                                                            if ($user->referral == true) {
                                                                $jenis = 'bumdesa';
                                                            } elseif ($user->referral == false) {
                                                                $jenis = 'bumdes-bersama';
                                                            }
                                                        @endphp

                                                        @foreach ($langganans->where('jenis', $jenis) as $langganan)
                                                            <option value="{{ $langganan->jumlah_bulan }}">
                                                                {{ $langganan->waktu }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="user{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="user{{ $user->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="user{{ $user->id }}Label">Ganti
                                                    Password
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="/admin/data-user/{{ $user->id }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label for="password">Password</label>
                                                    <input type="text" name="password" class="form-control">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </div>
    </div>
@endsection
