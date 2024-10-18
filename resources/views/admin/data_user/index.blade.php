@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">

            <div class="pagetitle">
                <h1>Data User</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body mt-5">


                    <!-- Table with stripped rows -->
                    <table class="table datatable">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Sisa Langganan</th>
                                <th scope="col">Status</th>
                                <th scope="col">password</th>
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
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $interval->days }}</td>
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

                                    </tr>
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="user{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="user{{ $user->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="user{{ $user->id }}Label">Modal title
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
