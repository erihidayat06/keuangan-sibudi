<?php

namespace App\Http\Controllers;

use App\Models\Buk;
use App\Models\User;
use App\Models\Modal;
use App\Models\Bukbesar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modals = Modal::user()->get();

        return view(
            'modal.index',
            [
                'modals'  => $modals,
            ]
        );
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
        $validate = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
        ]);



        $validate['user_id'] = auth()->user()->id;
        if (Modal::create($validate)) {
            $user_id = auth()->user()->id;
            if (isset($validate['mdl_desa'])) {
                bukuUmum('Modal Tambah dari desa', 'debit', 'kas', 'pendanaan', $request->mdl_desa,  'modal', Modal::latest()->first()->id);
            } elseif (isset($validate['mdl_masyarakat'])) {
                bukuUmum('Modal Tambah dari masyarakat', 'debit', 'kas', 'pendanaan',  $request->mdl_masyarakat, 'modal', Modal::latest()->first()->id);
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
        $validate = $request->validate([
            'tahun' => 'required',
            'sumber' => 'required|string|max:100',
            'mdl_desa' => 'max:11',
            'mdl_masyarakat' => 'max:11',
        ]);


        $validate['user_id'] = auth()->user()->id;

        if (Modal::where('id', $modal->id)->update($validate)) {
            if ($modal->mdl_desa != null) {
                updateBukuUmum('modal', $modal->id, $request->mdl_desa);
            } elseif ($modal->mdl_masyarakat != null) {
                updateBukuUmum('modal', $modal->id, $request->mdl_masyarakat);
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
