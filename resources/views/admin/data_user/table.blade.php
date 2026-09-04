<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telepon</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
                <th>Desa</th>
                <th>Sisa Langganan (Hari)</th>
                <th>Status</th>
                <th>Password</th>
                <th>Langganan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                @php
                    $target = \Carbon\Carbon::parse($user->tgl_langganan);
                    $today = \Carbon\Carbon::today();
                    $remaining = $target->isPast() ? 0 : $today->diffInDays($target, false);
                    $remaining = max(0, (int) $remaining);
                @endphp
                <tr>
                    {{-- Penomoran urut dinamis menyesuaikan halaman --}}
                    <td>{{ $users->firstItem() + $loop->index }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profil->no_wa ?? '-' }}</td>
                    <td>{{ $user->profil->kabupaten ?? '-' }}</td>
                    <td>{{ $user->profil->kecamatan ?? '-' }}</td>
                    <td>{{ $user->profil->desa ?? '-' }}</td>
                    <td>{{ $remaining }}</td>
                    <td>
                        @if ($remaining <= 0)
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @else
                            <span class="badge bg-success">Aktif</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#user{{ $user->id }}">
                            Ubah Password
                        </button>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#langganan{{ $user->id }}">
                            Ubah Langganan
                        </button>
                    </td>
                    <td>
                        <form action="/admin/data-user/{{ $user->id }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin dihapus?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Ubah Langganan -->
                <div class="modal fade" id="langganan{{ $user->id }}" tabindex="-1"
                    aria-labelledby="langganan{{ $user->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="langganan{{ $user->id }}Label">Update Langganan -
                                    {{ $user->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="/admin/langganan/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="langganan" class="form-label">Pilih Paket Langganan</label>
                                        <select class="form-select" name="langganan">
                                            @php
                                                $jenis = $user->referral ? 'bumdesa' : 'bumdes-bersama';
                                            @endphp

                                            @foreach ($langganans->where('jenis', $jenis) as $langganan)
                                                <option value="{{ $langganan->jumlah_bulan }}">
                                                    {{ $langganan->waktu }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Ubah Password -->
                <div class="modal fade" id="user{{ $user->id }}" tabindex="-1"
                    aria-labelledby="user{{ $user->id }}Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="user{{ $user->id }}Label">Ganti Password -
                                    {{ $user->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="/admin/data-user/{{ $user->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted">Tidak ada data yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
