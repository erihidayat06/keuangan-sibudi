@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h1>Data User</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body mt-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="/admin/data-user/create" class="btn btn-sm btn-success">Tambah User</a>

                        <!-- Form Pencarian -->
                        <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2" style="max-width: 350px;">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari nama, email, lokasi..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="bi bi-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                                    class="btn btn-sm btn-secondary" title="Reset Search">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Navigasi Tab berdasarkan Query String URL -->
                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'aktif' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['status' => 'aktif', 'page' => null]) }}">
                                🟢 Aktif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status == 'nonaktif' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['status' => 'nonaktif', 'page' => null]) }}">
                                🔴 Tidak Aktif
                            </a>
                        </li>
                    </ul>

                    <!-- Memanggil Partial Table -->
                    @include('admin.data_user.table', ['users' => $users])

                    <!-- Navigasi Halaman (Pagination Links) -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
