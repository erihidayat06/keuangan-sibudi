<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDataUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
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
}
