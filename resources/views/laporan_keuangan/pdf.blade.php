<style>
    table {
        width: 100%;
    }

    .text-end {
        text-align: right;
    }

    #tandatangan tr td {

        border: 1px solid black;
        padding: 5px;
        vertical-align: bottom;
    }

    #tandatangan {
        text-align: center;
        border-collapse: collapse;
        margin-top: 50px;
    }
</style>

<table>
    <tr>
        <td colspan="2">
            <h2>H. Laporan Keuangan</h2>
        </td>
    </tr>
    <tr>
        <td>
            <p>{{ unitUsaha()['nm_bumdes'] }}</p>
        </td>

        <td class="text-end">
            Per 31 Desember {{ session('selected_year', date('Y')) }}
        </td>
    </tr>
</table>


@include('neraca.pdf')
<hr>
@include('laporan_laba_rugi.pdf')
<hr>
@include('laporan_arus_kas.pdf')
<hr>
@include('laporan_perubahan_modal.pdf')
<hr>
<table>
    <tr>
        <td class="text-end">
            Pebatan
        </td>
        <td class="text-end">
            {{ session('selected_year', date('Y') + 1) }}
        </td>
    </tr>
</table>
<table id="tandatangan">
    <tr>
        <td>Ditelaah</td>
        <td>Tanda Tangan</td>
        <td>Dibuat</td>
        <td>Tanda Tangan</td>
    </tr>
    <tr>
        <td>
            <p>Pengawas BUMDesa</p>
            <p>{{ unitUsaha()['nm_pengawas'] }}</p>
        </td>
        <td>(......................)</td>
        <td>
            <p>Bendahara BUMDesa</p>
            <p>{{ unitUsaha()['nm_bendahara'] }}</p>
        </td>
        <td>(......................)</td>
    </tr>
    <tr>
        <td>
            <p>Penasehat BUMDesa</p>
            <p>{{ unitUsaha()['nm_penasehat'] }}</p>
        </td>
        <td>(......................)</td>
        <td>
            <p>Direktur BUMDesa</p>
            <p>{{ unitUsaha()['nm_direktur'] }}</p>
        </td>
        <td>(......................)</td>
    </tr>
</table>
