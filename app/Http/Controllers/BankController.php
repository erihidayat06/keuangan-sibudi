<?php

namespace App\Http\Controllers;

use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kas = neraca()['kas'];
        $rekonsiliasi = Rekonsiliasi::user()->get();

        // Daftar bank yang wajib ada
        $requiredBanks = ['Bank BRI', 'Bank BNI', 'Bank Mandiri', 'Bank BCA'];

        // Ambil posisi yang sudah ada
        $existingBanks = $rekonsiliasi->pluck('posisi')->toArray();

        // Tambahkan yang belum ada
        foreach ($requiredBanks as $bank) {
            if (!in_array($bank, $existingBanks)) {
                Rekonsiliasi::create([
                    'user_id' => auth()->id(),
                    'posisi' => $bank,
                    'jumlah' => 0, // Default 0 jika tidak ada nilai awal
                ]);
            }
        }

        // Ambil ulang data setelah penambahan
        $rekonsiliasi = Rekonsiliasi::user()->get();

        return view('bank.index', ['kas' => $kas, 'rekonsiliasis' => $rekonsiliasi]);
    }



    public function exportPdf()
    {

        $kas = neraca()['kas'];
        $rekonsiliasi = Rekonsiliasi::user()->get();
        $data = ['kas' => $kas, 'rekonsiliasis' => $rekonsiliasi];

        // Gunakan facade PDF
        $pdf = PDF::loadView('bank.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rekonsiliasi = Rekonsiliasi::user()->get();
        return view('bank.tambah',  ['rekonsiliasis' => $rekonsiliasi]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|string'
        ]);
        $validated['user_id'] = auth()->user()->id;

        $rekon =   Rekonsiliasi::create($validated);

        if ($rekon) {
            histori(rendem(), 'rekonsiliasis', $validated, 'create', $rekon->id);
        }

        return redirect()->back()->with('success', 'Berhasil menambahakan posisi');
    }

    /**
     * Bayar the specified resource in storage.
     */
    public function bayar(Request $request, Rekonsiliasi $rekonsiliasi)
    {
        // 1. Validasi Input Request
        $validated = $request->validate([
            'jumlah' => ['required', 'string'],
            'aksi'   => ['required', 'in:+,-'],
        ], [
            'jumlah.required' => 'Jumlah nominal wajib diisi.',
            'aksi.required'   => 'Aksi transaksi wajib dipilih.',
            'aksi.in'         => 'Aksi transaksi tidak valid.',
        ]);

        // Format input jumlah (membersihkan format ribuan)
        $input_realisasi = (float) str_replace('.', '', $validated['jumlah']);

        // Validasi nominal harus lebih besar dari 0
        if ($input_realisasi <= 0) {
            return back()->withErrors(['jumlah' => 'Nominal harus lebih dari 0.'])->withInput();
        }

        // 2. Tentukan Jenis & Akun Transaksi
        if ($validated['aksi'] === '+') {
            $jumlah = $rekonsiliasi->jumlah + $input_realisasi;
            $jenis  = 'kredit';
            $akun   = 'Setor';
        } else {
            // Validasi agar saldo/jumlah tidak menjadi minus saat penarikan (-)
            if ($rekonsiliasi->jumlah < $input_realisasi) {
                return back()->withErrors(['jumlah' => 'Saldo/Jumlah tidak mencukupi untuk penarikan.'])->withInput();
            }

            $jumlah = $rekonsiliasi->jumlah - $input_realisasi;
            $jenis  = 'debit';
            $akun   = 'Tarik';
        }

        // 3. Penanganan Tanggal berdasarkan Sesi Tahun
        $selectedYear = session('selected_year', date('Y'));
        $tanggal = sprintf('%s-%s', $selectedYear, date('m-d'));

        // 4. Proses Transaksi dengan Database Transaction
        DB::transaction(function () use ($rekonsiliasi, $jumlah, $akun, $jenis, $input_realisasi, $tanggal) {
            $idHistori = rendem();

            // Update saldo Rekonsiliasi
            $rekonsiliasi->update(['jumlah' => $jumlah]);

            // Catat ke Buku Umum
            $buk = bukuUmum($akun, $jenis, 'kas', 'tidak_dihitung', $input_realisasi, null, null, $tanggal);

            // Catat Histori
            histori($idHistori, 'rekonsiliasis', $rekonsiliasi->toArray(), 'update', $rekonsiliasi->id);
            histori($idHistori, 'buks', ['nilai' => $rekonsiliasi->nilai], 'create', $buk->id);
        });

        // 5. Response / Redirect
        $pesan = $validated['aksi'] === '+'
            ? 'Setor rekonsiliasi berhasil ditambahkan.'
            : 'Penarikan rekonsiliasi berhasil diproses.';

        return back()->with('success', $pesan);
    }
    /**
     * Display the specified resource.
     */
    public function show(Rekonsiliasi $rekonsiliasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekonsiliasi $rekonsiliasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rekonsiliasi $rekonsiliasi)
    {
        $validated = $request->validate([
            'posisi' => 'required|string',
        ]);
        $validated['user_id'] = auth()->user()->id;
        $validated['jumlah'] = str_replace('.', '', $request->jumlah);

        Rekonsiliasi::where('id', $rekonsiliasi->id)->update($validated);
        histori(rendem(), 'rekonsiliasis', $rekonsiliasi->toArray(), 'update', $rekonsiliasi->id);
        return redirect()->back()->with('success', 'Rekonsiliasi berhasil diupdate.');
    }
    /**
     * UpdateJumlah the specified resource in storage.
     */
    public function updateJumlah(Request $request, Rekonsiliasi $rekonsiliasi)
    {
        $rekonsiliasiData = $request->input('rekonsiliasi');

        $id = rendem();

        foreach ($rekonsiliasiData as $data) {
            $dataRekon = Rekonsiliasi::find($data['id']);
            $rekon = Rekonsiliasi::where('id', $data['id'])->update(['jumlah' => $data['jumlah']]);

            if ($rekon) {
                histori($id, 'rekonsiliasis', ['jumlah' => $dataRekon->jumlah], 'update', $data['id']);
            }
        }

        return redirect()->back()->with('success', 'Rekonsiliasi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekonsiliasi $rekonsiliasi)
    {
        histori(rendem(), 'rekonsiliasis', $rekonsiliasi->toArray(), 'delete', $rekonsiliasi->id);
        $rekonsiliasi->delete();

        return redirect()->back()->with('error', 'Rekonsiliasi berhasil dihapus.');
    }
}
