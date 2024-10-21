<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ekuit;
use App\Models\Profil;
use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDataUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->latest()->get();
        return view('admin.data_user.index', ['users' => $users]);
    }

    public function ubahPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required'
        ]);

        $validated['password'] = Hash::make($request->password);

        User::where('id', $user->id)->update($validated);

        return redirect('/admin/data-user')->with('success', $user->name . ' Berhasil dirubah passwordnya');
    }

    public function create()
    {
        return view('admin.data_user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['tgl_langganan'] = date('Y-m-d', strtotime('+3 months'));
        $validated['status'] = true;

        $userId = auth()->user()->id;

        $existingEkuit = Ekuit::where('user_id', $userId)->first();

        if (!$existingEkuit) {
            Ekuit::create(['user_id' => $userId]);
        }

        $rekonsiliasi = Rekonsiliasi::where('user_id', $userId)->first();

        if (!$rekonsiliasi) {
            Rekonsiliasi::insert([
                ['posisi' => 'Kas di tangan', 'user_id' => $userId],
                ['posisi' => 'Bank Jateng', 'user_id' => $userId]
            ]);
        }
        if (User::create($validated)) {
            $userId = User::latest()->first()->id;

            $existingEkuit = Ekuit::where('user_id', $userId)->first();

            if (!$existingEkuit) {
                Ekuit::create(['user_id' => $userId]);
            }

            $rekonsiliasi = Rekonsiliasi::where('user_id', $userId)->first();

            if (!$rekonsiliasi) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $userId],
                    ['posisi' => 'Bank Jateng', 'user_id' => $userId]
                ]);
            }
            Profil::create(['user_id' => User::latest()->first()->id]);
        }

        return redirect('/admin/data-user')->with('success', 'User Berhasil di tambah');
    }
}
