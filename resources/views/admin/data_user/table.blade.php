<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telepon</th>
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
        @php $i = 1; @endphp
        @forelse ($users as $user)
            @if ($user->role != 'admin')
                @php
                    $target = new DateTime($user->tgl_langganan);
                    $today = new DateTime();
                    $remaining = $target < $today ? 0 : $today->diff($target)->days;
                @endphp
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->profil->no_wa ?? '-' }}</td>
                    <td>{{ $user->profil->kecamatan ?? '-' }}</td>
                    <td>{{ $user->profil->desa ?? '-' }}</td>
                    <td>{{ $remaining }}</td>
                    <td>
                        {!! $remaining <= 0 ? '<span class="text-danger">Tidak Aktif</span>' : '<span class="text-success">Aktif</span>' !!}
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#user{{ $user->id }}">
                            Ubah Password
                        </a>
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#langganan{{ $user->id }}">
                            Ubah Langganan
                        </a>
                    </td>
                    <td>
                        <form action="/admin/data-user/{{ $user->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin dihapus?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="11" class="text-center text-muted">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
