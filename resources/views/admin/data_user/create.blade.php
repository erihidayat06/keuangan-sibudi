@extends('layouts.main')

@section('container')
    <div class="card mb-3 col-lg-8">

        <div class="card-body">

            <div class="pt-4 pb-2">
                <h5 class="card-title text-center pb-0 fs-4">Buat Akun</h5>
                <p class="text-center small">Masukkan detail pribadi Anda untuk membuat akun</p>
            </div>

            <form method="POST" action="/admin/data-user">
                @csrf
                <div class="col-12 mt-3">
                    <label for="yourName" class="form-label">Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-12 mt-3">
                    <label for="yourEmail" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <div class="col-12 mt-3">
                    <label for="yourPassword" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-12 mt-3">
                    <label for="yourPassword" class="form-label">Confirmasi Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                </div>


                <div class="col-12 mt-3">
                    <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                </div>

            </form>

        </div>
    </div>
@endsection
