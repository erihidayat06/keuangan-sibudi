<?php

namespace App\Http\Controllers;

use App\Services\DemoSandboxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    /**
     * Reset data praktikum demo kembali ke kondisi awal dan perpanjang waktu 1 jam.
     */
    public function reset(Request $request, DemoSandboxService $sandboxService)
    {
        $user = Auth::user();

        if (!$user || !$user->is_demo) {
            return redirect()->back()->with('error', 'Aksi ini hanya tersedia untuk akun sesi praktikum.');
        }

        $sandboxService->resetPracticeData($user);

        return redirect()->back()->with('success', 'Data praktikum berhasil di-reset! Sesi latihan Anda telah diperbarui menjadi 1 jam.');
    }

    /**
     * Beralih secara instan antara mode BUMDesa (referral=1) dan Koperasi (referral=0).
     */
    public function switchMode($referral, Request $request, DemoSandboxService $sandboxService)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !$currentUser->is_demo) {
            return redirect()->back()->with('error', 'Aksi ini hanya tersedia untuk akun sesi praktikum.');
        }

        $targetReferral = ((int) $referral === 1) ? 1 : 0;
        $token = $currentUser->demo_token ?: ('demo_' . $currentUser->id);

        // Ambil nama tanpa prefix [Praktikum ...]
        $cleanName = preg_replace('/^\[Praktikum.*?\]\s*/i', '', $currentUser->name);

        $targetUser = $sandboxService->authenticateByToken($token, $targetReferral, $cleanName);

        if ($targetUser) {
            $entity = ($targetReferral === 1) ? 'BUMDesa' : 'Koperasi';
            return redirect('/')->with('success', "Berhasil beralih ke Mode Praktikum {$entity}!");
        }

        return redirect()->back();
    }
}
