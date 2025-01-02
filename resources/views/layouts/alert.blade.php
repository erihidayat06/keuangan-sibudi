@php
    use App\Models\Tutup;
    $tutup = Tutup::user()
        ->where('tahun', session('selected_year', date('Y')))
        ->get()
        ->first();
    if (isset($tutup)) {
        $neraca = json_decode($tutup->data, true);
    }

@endphp

@if (isset($tutup))
    @can('bumdes')
        <div class="alert {{ formatRupiah($neraca['total_aktiva']) != formatRupiah($neraca['passiva']) ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show"
            role="alert">
            {{ formatRupiah($neraca['total_aktiva']) != formatRupiah($neraca['passiva']) ? 'Neraca Belum Seimbang Mohon Periksa Setiap Akun |' : '' }}

            <strong> Aktiva : </strong>{{ formatRupiah($neraca['total_aktiva']) }} |
            <strong> passiva : </strong> {{ formatRupiah($neraca['passiva']) }}


            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endcan
@else
    @can('bumdes')
        <div class="alert {{ formatRupiah(neraca()['aktiva']) != formatRupiah(neraca()['passiva']) ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show"
            role="alert">
            {{ formatRupiah(neraca()['aktiva']) != formatRupiah(neraca()['passiva']) ? 'Neraca Belum Seimbang Mohon Periksa Setiap Akun |' : '' }}

            <strong> Aktiva : </strong>{{ formatRupiah(neraca()['aktiva']) }} |
            <strong> passiva : </strong> {{ formatRupiah(neraca()['passiva']) }}


            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endcan
@endif
