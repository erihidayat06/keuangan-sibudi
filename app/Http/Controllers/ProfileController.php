<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = auth()->user()->profil;
        return view('profile.index', ['profil' => $profile]);
    }

    // Update BUMDes data
    public function update(Request $request, Profil $profil)
    {
        // Validate the request data
        $request->validate([
            'nm_bumdes' => 'nullable|string|max:100',
            'desa' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'nm_direktur' => 'nullable|string|max:50',
            'nm_serkertaris' => 'nullable|string|max:50',
            'nm_bendahara' => 'nullable|string|max:50',
            'nm_pengawas' => 'nullable|string|max:50',
            'nm_penasehat' => 'nullable|string|max:50',
            'unt_usaha1' => 'nullable|string|max:50',
            'nm_kepyun1' => 'nullable|string|max:50',
            'unt_usaha2' => 'nullable|string|max:50',
            'nm_kepyun2' => 'nullable|string|max:50',
            'unt_usaha3' => 'nullable|string|max:50',
            'nm_kepyun3' => 'nullable|string|max:50',
            'unt_usaha4' => 'nullable|string|max:50',
            'nm_kepyun4' => 'nullable|string|max:50',
            'no_badan' => 'nullable|string|max:50',
            'no_perdes' => 'nullable|string|max:50',
            'no_sk' => 'nullable|string|max:50',
        ]);

        // Find the Bumdes by its ID
        $bumdes = Profil::findOrFail($profil->id);

        histori(rendem(), 'profils', $profil->toArray(), 'update', $profil->id);

        // Update the data in the database
        $bumdes->update([
            'nm_bumdes' => $request->nm_bumdes,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'nm_direktur' => $request->nm_direktur,
            'nm_serkertaris' => $request->nm_serkertaris,
            'nm_bendahara' => $request->nm_bendahara,
            'nm_pengawas' => $request->nm_pengawas,
            'nm_penasehat' => $request->nm_penasehat,
            'unt_usaha1' => $request->unt_usaha1,
            'nm_kepyun1' => $request->nm_kepyun1,
            'unt_usaha2' => $request->unt_usaha2,
            'nm_kepyun2' => $request->nm_kepyun2,
            'unt_usaha3' => $request->unt_usaha3,
            'nm_kepyun3' => $request->nm_kepyun3,
            'unt_usaha4' => $request->unt_usaha4,
            'nm_kepyun4' => $request->nm_kepyun4,
            'no_badan' => $request->no_badan,
            'no_perdes' => $request->no_perdes,
            'no_sk' => $request->no_sk,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Data BUMDes berhasil diperbarui.');
    }
}
