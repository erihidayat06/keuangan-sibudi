@php
    use App\Models\Tutup;
    use Illuminate\Support\Facades\Cache;

    // Ambil tahun yang dipilih
    $tahunDipilih = session('selected_year', date('Y'));

    // Cek apakah ada data di tabel 'Tutup'
    $tutup = Tutup::user()->where('tahun', $tahunDipilih)->first();

    if ($tutup) {
        // Jika ada, gunakan data dari tabel
        $neraca = json_decode($tutup->data, true);
    } else {
        // Jika tidak ada, ambil dari cache
        $neraca = Cache::get('neraca_' . $tahunDipilih, function () {
            return neracaAktiva();
        });
    }

    // Cek keseimbangan neraca
    $neracaSeimbang = formatRupiah($neraca['aktiva']) == formatRupiah($neraca['passiva']);
@endphp

@can('bumdes')
    <div class="alert {{ $neracaSeimbang ? 'alert-success' : 'alert-danger' }} alert-dismissible fade show" role="alert">
        {{ !$neracaSeimbang ? 'Neraca Belum Seimbang Mohon Periksa Setiap Akun |' : '' }}

        <strong> Aktiva : </strong>{{ formatRupiah($neraca['aktiva']) }} |
        <strong> Passiva : </strong> {{ formatRupiah($neraca['passiva']) }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endcan
