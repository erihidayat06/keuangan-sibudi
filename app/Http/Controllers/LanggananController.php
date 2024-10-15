<?php

namespace App\Http\Controllers;

use App\Models\Ekuit;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LanggananController extends Controller
{
    public function index()
    {
        return view('langganan.index');
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

        // Set harga berdasarkan durasi langganan
        switch ($duration) {
            case '1':
                $grossAmount = 24900;
                break;
            case '6':
                $grossAmount = 149400;
                break;
            case '12':
                $grossAmount = 298800;
                break;
            case '24':
                $grossAmount = 597600;
                break;
            case '48':
                $grossAmount = 1195200;
                break;
            default:
                $grossAmount = 24900;
        }

        // Set data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '081234567890',
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

        // Tampilkan respons
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil, langganan aktif sampai ' . $langganan_baru->translatedFormat('d F Y') . '.'
        ]);
    }
}
