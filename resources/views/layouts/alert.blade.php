@can('bumdes')
    <div class="alert {{ neraca()['aktiva'] != neraca()['passiva'] ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show"
        role="alert">
        {{ neraca()['aktiva'] != neraca()['passiva'] ? 'Neraca Belum Seimbang Mohon Periksa Setiap Akun |' : '' }}

        <strong> Aktiva : </strong>{{ formatRupiah(neraca()['aktiva']) }} |
        <strong> passiva : </strong> {{ formatRupiah(neraca()['passiva']) }}


        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endcan
