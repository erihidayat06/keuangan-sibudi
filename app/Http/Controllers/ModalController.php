<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modals = Modal::user()->get();

        return view('modal.index', ['modals'  => $modals]);
    }

    public function exportPdf()
    {

        $modals = Modal::user()->get();
        $data = [
            'modals'  => $modals,
        ];

        // Gunakan facade PDF
        $pdf = PDF::loadView('modal.pdf', $data);

        // Mengunduh PDF dengan nama "laporan.pdf"
        return $pdf->stream('laporan.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        $validated  = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
            'mdl_bersama' => 'max:11',
        ]);

        $validated['created_at'] = created_at();

        $validated['user_id'] = auth()->user()->id;
        if (Modal::create($validated) && $request->has('no_kas')) {

            if (isset($validated['mdl_desa'])) {
                bukuUmum('Modal Tambah dari desa', 'debit', 'kas', 'pendanaan', $request->mdl_desa,  'modal', Modal::latest()->first()->id, created_at());
            } elseif (isset($validated['mdl_masyarakat'])) {
                bukuUmum('Modal Tambah dari masyarakat', 'debit', 'kas', 'pendanaan',  $request->mdl_masyarakat, 'modal', Modal::latest()->first()->id, created_at());
            } elseif (isset($validated['mdl_bersama'])) {
                bukuUmum('Modal Tambah dari BUMDesa bersama', 'debit', 'kas', 'pendanaan',  $request->mdl_bersama, 'modal', Modal::latest()->first()->id, created_at());
            }
        };


        return redirect('/modal');
    }

    /**
     * Display the specified resource.
     */
    public function show(modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(modal $modal)
    {
        return view('modal.edit', [
            'modal' => $modal
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, modal $modal)
    {
        $validated = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
            'mdl_bersama' => 'max:11',
        ]);


        $validated['user_id'] = auth()->user()->id;

        if (Modal::where('id', $modal->id)->update($validated)) {
            if ($modal->mdl_desa != null) {
                updateBukuUmum('modal', $modal->id, $request->mdl_desa);
            } elseif ($modal->mdl_masyarakat != null) {
                updateBukuUmum('modal', $modal->id, $request->mdl_masyarakat);
            } elseif ($modal->mdl_bersama != null) {
                updateBukuUmum('modal', $modal->id, $request->mdl_bersama);
            };
        };
        return redirect('/modal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(modal $modal)
    {

        Modal::where('id', $modal->id)->delete();
        return redirect()->back();
    }
}
