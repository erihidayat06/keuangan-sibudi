<?php

namespace App\Http\Controllers;

use App\Models\Rasio;
use App\Models\Proker;
use App\Models\Alokasi;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProgramRequest;

class PenambahanModalController extends Controller
{
    public function penambahanModal()
    {
        $title = 'G. RENCANA PENAMBAHAN MODAL';
        $back = '/proker/rencana/kegiatan';
        $proker = Proker::user()->where('tahun', session('selected_year', date('Y')))->get()->first();
        $alokasis = Alokasi::user()->where('tahun', session('selected_year', date('Y')))->get();
        $rasios = Rasio::user()->where('tahun', session('selected_year', date('Y')))->get();
        return view('proker.penambahan_modal', [
            'title' => $title,
            'back' => $back,
            'alokasis' => $alokasis,
            'rasios' => $rasios,
            'proker' => $proker,
        ]);
    }

    public function penambahanModalUpdate(Request $request, Proker $proker)
    {
        // Validasi input dari request untuk memastikan data yang dibutuhkan ada
        $validated = $request->validate([
            'kualititif' => 'nullable|string',
            'strategi' => 'nullable|string',
            'unit_usaha' => 'nullable|string',
            'status_unit' => 'nullable|string',
            'jumlah' => 'nullable|numeric',
            'aspek_pasar' => 'nullable|string',
            'aspek_keuangan' => 'nullable|string',
            'aspek_lainya' => 'nullable|string',
            'strategi_pemasaran' => 'nullable|string',
            'kesimpulan' => 'nullable|string',
        ]);

        // Update hanya data yang diterima dan valid
        $proker->update($validated);

        return back();
    }

    public function updateStatus(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'id' => 'required|exists:prokers,id',
            'status' => 'required|string',
        ]);

        // Temukan data berdasarkan ID
        $proker = Proker::find($validated['id']);

        // Perbarui status
        $proker->status = $validated['status'];
        $proker->save();

        // Kembalikan respon sukses
        return response()->json(['message' => 'Status berhasil diperbarui!']);
    }

    public function alokasiStore(StoreProgramRequest $request)
    {
        $validated = $request->validated();
        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();
        foreach ($validated['data'] as $program => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Alokasi::create($value);
        }

        return redirect()->back()->with('success', 'Data program berhasil disimpan!');
    }
    public function resikoStore(Request $request)
    {
        $validated = $request->all();
        $tahun = session('selected_year', date('Y'));
        $user_id = auth()->id();
        foreach ($validated['data'] as $program => $value) {
            $value['tahun'] = $tahun;
            $value['user_id'] = $user_id;
            Rasio::create($value);
        }

        return redirect()->back()->with('success', 'Data program berhasil disimpan!');
    }
}
