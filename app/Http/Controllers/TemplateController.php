<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Services\DemoSandboxService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    /**
     * Menampilkan halaman yang memuat modal templates atau otomatis login jika membawa token praktikum
     */
    public function index(Request $request, DemoSandboxService $sandboxService)
    {
        // Jika terdapat parameter ?token=..., proses login otomatis praktikum
        if ($request->filled('token')) {
            $token = trim($request->query('token'));
            // Ambil referral dari query/input (?referral=1 atau ?referral=0, atau role/tipe)
            $referralParam = $request->query('referral', $request->input('referral', $request->query('role', $request->input('role'))));

            Log::info('[PortalBUMDes SSO] Token login diminta.', [
                'token_prefix' => substr($token, 0, 8) . '...',
                'referral'     => $referralParam,
                'ip'           => $request->ip(),
            ]);

            try {
                // Logout user yang sedang login agar tidak konflik session
                if (Auth::check()) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }

                $user = $sandboxService->authenticateByToken($token, $referralParam);

                if ($user) {
                    // Regenerasi session ID setelah login untuk keamanan
                    $request->session()->regenerate();

                    $entityLabel = ((int) $user->referral === 0) ? 'Koperasi' : 'BUMDesa';

                    Log::info('[PortalBUMDes SSO] Login berhasil.', [
                        'user_id'  => $user->id,
                        'name'     => $user->name,
                        'referral' => $user->referral,
                        'entitas'  => $entityLabel,
                    ]);

                    return redirect('/')
                        ->with('success', "Selamat datang di Sesi Praktikum ({$entityLabel}) PortalBUMDes Academy! Sesi Anda aktif selama 1 jam.");
                }

                Log::warning('[PortalBUMDes SSO] Token tidak ditemukan di database portal.', [
                    'token_prefix' => substr($token, 0, 8) . '...',
                ]);

                return redirect('/login')
                    ->with('error', 'Token praktikum PortalBUMDes tidak valid atau tidak ditemukan. Silakan minta link baru dari instruktur.');

            } catch (\Throwable $e) {
                Log::error('[PortalBUMDes SSO] Error saat memproses token.', [
                    'error'        => $e->getMessage(),
                    'token_prefix' => substr($token, 0, 8) . '...',
                ]);

                return redirect('/login')
                    ->with('error', 'Gagal memproses token praktikum: ' . $e->getMessage());
            }
        }

        // ambil semua kategori beserta sub kategori (eager load)
        $categories = Kategori::with(['subCategories' => function($q){
            $q->orderBy('id');
        }])->orderBy('id')->get();

        return view('auth.login', compact('categories'));
    }
}
