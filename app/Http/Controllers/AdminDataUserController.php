<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ekuit;
use App\Models\Profil;
use App\Models\Langganan;
use App\Models\Rekonsiliasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminDataUserController extends Controller
{
    public function index($kecamatan)
    {

        $users = User::with('profil')
            ->whereHas('profil', function ($query) use ($kecamatan) {
                $query->where('kecamatan', $kecamatan);
            })
            ->latest()
            ->get();

        $langganans = Langganan::orderBy('jumlah_bulan', 'asc')->get();
        return view('admin.data_user.index', ['users' => $users, 'langganans' => $langganans]);
    }
    public function allUser(Request $request)
    {
        $status = $request->query('status', 'aktif');
        $search = $request->query('search');
        $today = Carbon::today()->toDateString();

        $users = User::where('role', '!=', 'admin')
            ->with('profil')
            // Filter Berdasarkan Tab Status
            ->when($status === 'aktif', function ($query) use ($today) {
                return $query->whereDate('tgl_langganan', '>=', $today);
            })
            ->when($status === 'nonaktif', function ($query) use ($today) {
                return $query->whereDate('tgl_langganan', '<', $today);
            })
            // Filter Berdasarkan Pencarian (Search)
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profil', function ($p) use ($search) {
                            $p->where('no_wa', 'like', "%{$search}%")
                                ->orWhere('kabupaten', 'like', "%{$search}%")
                                ->orWhere('kecamatan', 'like', "%{$search}%")
                                ->orWhere('desa', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            // Mempertahankan parameter ?status=...&search=... di tombol pagination
            ->withQueryString();

        $langganans = Langganan::orderBy('jumlah_bulan', 'asc')->get();

        return view('admin.data_user.allUser', compact('users', 'langganans', 'status', 'search'));
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

    public function langganan(Request $request, User $user)
    {
        $validated = $request->validate([]);

        // Get the current date
        $currentDate = now();

        // Check if the user's subscription date (`tanggal_langganan`) is greater than the current date
        if ($user->tgl_langganan && $user->tgl_langganan > $currentDate) {
            // Add the subscription months to the existing `tanggal_langganan`
            $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $request->langganan . ' months', strtotime($user->tgl_langganan)));
        } else {
            // Add the subscription months to today's date
            $validated['tgl_langganan'] = date('Y-m-d', strtotime('+' . $request->langganan . ' months', strtotime($currentDate)));
        }

        $validated['status'] = true;


        User::where('id', $user->id)->update($validated);

        return redirect('/admin/data-user')->with('success', $user->name . ' Berhasil diupdate Langganan');
    }

    public function create()
    {
        return view('admin.data_user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|min:3|max:100',
            'email'      => 'required|email|unique:users,email',
            'referral'   => 'required',
            'no_wa'      => 'required',
            'kabupaten'  => 'required',
            'kecamatan'  => 'required',
            'desa'       => 'required',
            'password'   => 'required|min:6|confirmed'
        ]);

        // Hash password
        $validated['password'] = Hash::make($request->password);

        // Set tanggal langganan ke hari ini (tanggal dibuat)
        $validated['tgl_langganan'] = date('Y-m-d');

        // status default (sesuai kode sebelumnya)
        $validated['status'] = false;

        // Buat user
        $user = User::create($validated);

        if ($user) {
            // Ekuit (jika belum ada)
            Ekuit::firstOrCreate(['user_id' => $user->id]);

            // Rekonsiliasi awal
            if (!Rekonsiliasi::where('user_id', $user->id)->exists()) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $user->id],
                    ['posisi' => 'Bank Jateng', 'user_id' => $user->id]
                ]);
            }

            // Profil
            Profil::create([
                'user_id'   => $user->id,
                'no_wa'     => $validated['no_wa'],
                'kabupaten' => $validated['kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'desa'      => $validated['desa'] ?? null,
            ]);

            return redirect('/login')->with('success', 'User berhasil ditambahkan. Silakan login.');
        }

        return back()->withInput()->with('error', 'Gagal membuat user. Silakan coba lagi.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('error', 'User Berhasil di hapus');
    }
}
