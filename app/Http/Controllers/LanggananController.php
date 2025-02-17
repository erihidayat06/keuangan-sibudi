<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Ekuit;
use App\Models\Profil;
use App\Models\Langganan;
use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;

class LanggananController extends Controller
{
    public function index()
    {

        if (auth()->user()->referral) {
            $jenis = 'bumdesa';
        } else {
            $jenis = 'bumdes-bersama';
        }
        $langganans = Langganan::where('jenis', $jenis)->orderBy('jumlah_bulan', 'asc')->get();
        $langganan_pertama = Langganan::where('jenis', $jenis)->orderBy('jumlah_bulan', 'asc')->first();

        return view('langganan.index', ['langganans' => $langganans, 'mulai' => $langganan_pertama->harga]);
    }

    public function createTransaction(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil durasi langganan dari request
        $duration = $request->input('subscription_duration');
        if (auth()->user()->referral) {
            $jenis = 'bumdesa';
        } else {
            $jenis = 'bumdes-bersama';
        }

        // Cari data langganan sesuai dengan durasi yang dipilih
        $langganan = Langganan::where('jenis', $jenis)->where('jumlah_bulan', $duration)->first();
        if (!isset($langganan->harga)) {
            $langganan_harga = 12900; // Harga default jika tidak ditemukan
            $nama_produk = "Langganan Default";
        } else {
            $langganan_harga = $langganan->harga;
            $nama_produk = "Langganan " . $langganan->jumlah_bulan . " Bulan";
        }


        // Set data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $langganan_harga + 6500, // Total transaksi
            ],
            'item_details' => [
                [
                    'id' => $langganan->id,
                    'price' => $langganan_harga,
                    'quantity' => 1,
                    'name' => $nama_produk,
                ],
                [
                    'id' => $langganan->id,
                    'price' => 6500,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ],
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'last_name' => '',
                'email' => auth()->user()->email,
                'phone' => '', // Tambahkan nomor telepon jika tersedia
            ],
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Kembalikan view dengan Snap Token
        return response()->json(['snapToken' => $snapToken]);
    }


    public function langgananSuccess(Request $request)
    {
        // Validasi input
        $request->validate([
            'days' => 'required|integer|min:1',
            'transaction_id' => 'required|string', // Jika menggunakan ID transaksi
        ]);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Cek apakah tgl_langganan ada dan valid
        if ($user->tgl_langganan) {
            $langganan_user = Carbon::createFromFormat('Y-m-d', $user->tgl_langganan);
        } else {
            $langganan_user = Carbon::now(); // Jika belum ada, mulai dari sekarang
        }

        // Cek jika tanggal langganan kurang dari hari ini
        if ($langganan_user->isPast()) {
            // Jika tgl_langganan sudah lewat, mulai dari hari ini
            $langganan_baru = Carbon::now()->addDays($request->days * 30);
        } else {
            // Jika tgl_langganan masih berlaku, tambahkan jumlah hari
            $langganan_baru = $langganan_user->addDays($request->days * 30);
        }

        // Update status dan tgl_langganan user
        User::where('id', $user->id)->update([
            'status' => true,
            'tgl_langganan' => $langganan_baru->format('Y-m-d') // Simpan dalam format Y-m-d
        ]);

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





        // Tampilkan respons
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil, langganan aktif sampai ' . $langganan_baru->translatedFormat('d F Y') . '.'
        ]);
    }
}
