@extends('layouts_proker.main')

@section('container')
    @php
        $tambah = 0;
        $pades = 0;
        $lainya = 0;
        if (isset($ekuitas->akumulasi) and isset($ekuitas->pades) and isset($ekuitas->lainya)) {
            $tambah = $laba_berjalan * ($ekuitas->akumulasi / 100);
            $pades = $laba_berjalan * ($ekuitas->pades / 100);
            $lainya = $laba_berjalan * ($ekuitas->lainya / 100);
        }
        $modal_akhir = $tambah + $modal_desa + $modal_masyarakat;
    @endphp
    <p>1. Kuantitatif</p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Indikator</th>
                <th>Sasaran Keja</th>
                <th>Capaian Tahun</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Proyeksi Omset</td>
                <td></td>
                <td></td>
            </tr>
            @php
                $totalOmset = 0;
            @endphp

            @foreach ($units as $unit)
                @php
                    $omset = json_decode($target->omset ?? '{}', true)['pu' . $unit->kode] ?? 0;
                    $totalOmset += intval($omset);
                @endphp
                <tr>
                    <td></td>
                    <td>{{ $unit->nm_unit }}</td>
                    <td class="text-end">
                        {{ formatRupiah(old('pu' . $unit->kode, json_decode($target->omset ?? '{}', true)['pu' . $unit->kode] ?? 0)) }}
                    </td>
                    <td class="text-end red-text">
                        {{ formatRupiah(array_sum($pendapatan['pu' . $unit->kode] ?? [])) }}
                    </td>
                </tr>
            @endforeach

            <tr class="border-bottom">
                <td></td>
                <td>Total Omset</td>
                <td class="text-end">{{ formatRupiah($totalOmset) }}</td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['pu'] ?? 0) }}</td>
            </tr>

            <tr>
                <td>2</td>
                <td>Proyeksi Pembiayaan </td>
                <td></td>
                <td></td>
            </tr>
            @php
                $biaya = 0;
            @endphp

            @foreach ($units as $unit)
                @php
                    $pembiayaan = json_decode($target->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0;
                    $pembiayaan += intval($pembiayaan);
                @endphp
                <tr>
                    <td></td>
                    <td>Biaya Ops {{ $unit->nm_unit }}</td>
                    <td class="text-end">
                        {{ formatRupiah(old('bo' . $unit->kode, json_decode($target->pembiayaan ?? '{}', true)['bo' . $unit->kode] ?? 0)) }}
                    </td>
                    <td class="text-end red-text">
                        {{ formatRupiah(array_sum($pendapatan['bo' . $unit->kode] ?? [])) }}
                    </td>
                </tr>
            @endforeach

            <tr class="border-bottom">
                <td></td>
                <td>Total Biaya Operasional</td>
                <td class="text-end">{{ formatRupiah($biaya ?? 0) }}</td>
                <td class="text-end red-text">{{ formatRupiah($pendapatanTahun['bo'] ?? 0) }}</td>
            </tr>

            <tr>
                <td></td>
                <td>Gaji/Honor Pengurus</td>
                <td class="text-end">{{ formatRupiah(old('gaji', $target->gaji ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno1'] ?? [])) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>ATK dan Fotocopy</td>
                <td class="text-end">{{ formatRupiah(old('atk', $target->atk ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno2'] ?? [])) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Rapat-rapat</td>
                <td class="text-end">{{ formatRupiah(old('rapat', $target->rapat ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno3'] ?? [])) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Akumulasi Penyusutan</td>
                <td class="text-end">{{ formatRupiah(old('penyusutan', $target->penyusutan ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah($akumulasi_penyusutan ?? 0) }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Lain-lain</td>
                <td class="text-end">{{ formatRupiah(old('lain', $target->lain ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah(array_sum($pendapatan['bno4'] ?? [])) }}</td>
            </tr>

            <tr>
                <td>3</td>
                <td>

                    Proyeksi Laba/Rugi

                </td>
                <td class="text-end">{{ formatRupiah(old('laba', $target->laba ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah($totalLabaRugi ?? 0) }}</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Proyeksi Aset</td>
                <td class="text-end">{{ formatRupiah(old('aset', $target->aset ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah($aktiva ?? 0) }}</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Proyeksi Pades</td>
                <td class="text-end">{{ formatRupiah(old('pades', $target->pades ?? 0)) }}</td>
                <td class="text-end red-text">{{ formatRupiah($pades ?? 0) }}</td>
            </tr>
        </tbody>
    </table>
@endsection
