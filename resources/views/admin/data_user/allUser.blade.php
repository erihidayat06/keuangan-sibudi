@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <div class="pagetitle">
                <h1>Data User</h1>
            </div>

            <div class="card overflow-auto">
                <div class="card-body mt-4">

                    <a href="/admin/data-user/create" class="btn btn-sm btn-success mb-3">Tambah User</a>

                    <!-- Tab Navigation -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-aktif-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-aktif" type="button" role="tab" aria-controls="pills-aktif"
                                aria-selected="true">
                                🟢 Aktif
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-nonaktif-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-nonaktif" type="button" role="tab"
                                aria-controls="pills-nonaktif" aria-selected="false">
                                🔴 Tidak Aktif
                            </button>
                        </li>
                    </ul>

                    @include('admin.data_user.table')
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
