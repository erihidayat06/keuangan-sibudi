<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::user()->get();
        return view('unit_usaha.index', ['units' => $units]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unit_usaha.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =  $request->validate([
            'nm_unit' => 'required|string|max:255',
            'kepala_unit' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->user()->id;

        $validated['kode'] =   $this->getInitials($request->nm_unit);

        Unit::create($validated);

        return redirect('/unit')->with('success', 'Unit berhasil ditambahkan!');
    }

    function getInitials($string)
    {
        // Pecah string dan ambil huruf pertama tiap kata dengan array_map
        $initials = array_map(function ($word) {
            return strtolower($word[0]); // Ambil huruf pertama dan jadikan huruf kecil
        }, explode(" ", $string));

        // Gabungkan hasil menjadi satu string
        $initialsString = implode("", $initials);

        // Tambahkan nomor acak 4 digit di belakangnya
        $randomNumber = rand(1000, 9999);

        return $initialsString . $randomNumber;
    }


    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('unit_usaha.edit', ['unit' => $unit]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated =  $request->validate([
            'nm_unit' => 'required|string|max:255',
            'kepala_unit' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->user()->id;

        Unit::where('id', $unit->id)->update($validated);

        return redirect('/unit')->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect('/unit')->with('error', 'Unit berhasil dihapus!');
    }
}