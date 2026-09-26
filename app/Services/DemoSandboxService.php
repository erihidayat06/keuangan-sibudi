<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoSandboxService
{
    /**
     * List of tables using `user_id`.
     */
    protected static array $userIdTables = [
        'akps',
        'aktivalains',
        'alokasis',
        'bangunans',
        'bdmuks',
        'buks',
        'dithns',
        'ekuits',
        'hutangs',
        'investasis',
        'kebutuhans',
        'kerjasamas',
        'lpjs',
        'modals',
        'orders',
        'penjualans',
        'persediaans',
        'pinjamans',
        'piutangs',
        'profils',
        'programs',
        'prokers',
        'rasios',
        'rekonsiliasis',
        'targets',
        'tutups',
        'units',
    ];

    /**
     * Authenticate or create a demo user using the portal token.
     * Untuk bumdespro: user_roles_id = 2 (bumdes)
     */
    public function authenticateByToken(string $token): ?User
    {
        // 0. Pastikan migrasi kolom is_demo telah dijalankan
        if (!Schema::hasColumn('users', 'is_demo')) {
            throw new \Exception("Kolom 'is_demo' belum ada di tabel users. Harap jalankan migrasi: php artisan migrate --path=database/migrations/2026_09_14_000002_add_demo_fields_to_users_table.php");
        }

        // 1. Cari user di database portal
        $portalUser = $this->findPortalUser($token);

        if (!$portalUser) {
            return null;
        }

        // 2. Cek apakah user demo sudah ada untuk token / portal_user_id ini
        $user = User::where('is_demo', true)
            ->where(function ($query) use ($token, $portalUser) {
                $query->where('demo_token', $token)
                      ->orWhere('portal_user_id', $portalUser->id);
            })
            ->first();

        if ($user) {
            // Cek apakah sesi sebelumnya sudah habis (> 1 jam), bersihkan data lama
            if ($user->demo_expires_at && now()->greaterThan($user->demo_expires_at)) {
                $this->purgeUserData($user->id);
            }

            // Perbarui token, masa aktif 1 jam dari sekarang
            $user->update([
                'name'            => '[Praktikum] ' . $portalUser->name,
                'demo_token'      => $token,
                'portal_user_id'  => $portalUser->id,
                'demo_expires_at' => now()->addHour(),
                'status'          => 1,
                'tgl_langganan'   => now()->addDays(30),
                'role'            => 'bumdes',
                'user_roles_id'   => $user->user_roles_id ?: 2,
            ]);
        } else {
            // 3. Buat user demo baru terisolasi untuk peserta ini
            $email = 'demo_' . $portalUser->id . '_' . Str::random(5) . '@academy.portal';

            $user = User::create([
                'name'               => '[Praktikum] ' . $portalUser->name,
                'email'              => $email,
                'password'           => bcrypt(Str::random(16)),
                'role'               => 'bumdes',
                'status'             => 1,
                'tgl_langganan'      => now()->addDays(30),
                'user_roles_id'      => 2,
                'is_demo'            => true,
                'demo_token'         => $token,
                'portal_user_id'     => $portalUser->id,
                'demo_expires_at'    => now()->addHour(),
                'nama_bumdes'        => 'BUMDes Praktikum ' . $portalUser->name,
                'alamat_bumdes'      => 'Desa Praktikum Academy',
                'nomor_hukum_bumdes' => 'AHU-0000.PRAKTIKUM.2026',
            ]);
        }

        // Inisialisasi data dasar
        $this->initializeBasicData($user);

        // Login pengguna ke session
        Auth::login($user);

        return $user;
    }

    /**
     * Inisialisasi data profil dan rekonsiliasi awal untuk praktikum.
     */
    public function initializeBasicData(User $user): void
    {
        $userId = $user->id;

        if (class_exists(\App\Models\Profil::class)) {
            if (!\App\Models\Profil::where('user_id', $userId)->exists()) {
                \App\Models\Profil::create([
                    'user_id'            => $userId,
                    'nama_bumdes'        => $user->nama_bumdes ?: 'BUMDes Praktikum',
                    'alamat_bumdes'      => $user->alamat_bumdes ?: 'Desa Praktikum Academy',
                    'nomor_hukum_bumdes' => $user->nomor_hukum_bumdes ?: 'AHU-0000.PRAKTIKUM',
                ]);
            }
        }

        if (class_exists(\App\Models\Rekonsiliasi::class)) {
            if (!\App\Models\Rekonsiliasi::where('user_id', $userId)->exists()) {
                \App\Models\Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $userId, 'jumlah' => 0, 'created_at' => now(), 'updated_at' => now()],
                    ['posisi' => 'Bank',           'user_id' => $userId, 'jumlah' => 0, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
        }
    }

    /**
     * Bersihkan seluruh rekaman transaksi yang dibuat oleh user demo tertentu.
     */
    public function purgeUserData(int $userId): void
    {
        foreach (self::$userIdTables as $table) {
            try {
                if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_id')) {
                    DB::table($table)->where('user_id', $userId)->delete();
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal membersihkan tabel {$table} untuk user {$userId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Reset data praktikum dan mulai ulang sesi 1 jam.
     */
    public function resetPracticeData(User $user): void
    {
        $this->purgeUserData($user->id);
        $user->update([
            'demo_expires_at' => now()->addHour(),
        ]);
        $this->initializeBasicData($user);
    }

    /**
     * Cari dan bersihkan semua sesi demo yang sudah kedaluwarsa (> 1 jam).
     */
    public function cleanupExpiredSessions(): int
    {
        $expiredUsers = User::where('is_demo', true)
            ->where('demo_expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredUsers as $user) {
            $this->purgeUserData($user->id);
            $user->delete();
            $count++;
        }

        return $count;
    }

    /**
     * Mencari user dari database portal, mendukung koneksi sekunder dan auto-discovery prefix cPanel.
     */
    public function findPortalUser(string $token)
    {
        // 1. Coba koneksi sekunder 'portal' yang didefinisikan di config/database.php
        try {
            $portalUser = DB::connection('portal')
                ->table('users')
                ->where('bumdespro2_token', $token)
                ->first();

            if ($portalUser) {
                return $portalUser;
            }
        } catch (\Throwable $e) {
            Log::info('Koneksi portal eksplisit gagal, mencoba auto-discovery database: ' . $e->getMessage());
        }

        // 2. Auto-discovery schema di server MySQL yang sama (misal u110981049_portal_...)
        try {
            $databases = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%portal%'");
            foreach ($databases as $db) {
                $schemaName = $db->SCHEMA_NAME;
                try {
                    $portalUser = DB::table("{$schemaName}.users")
                        ->where('bumdespro2_token', $token)
                        ->first();
                    if ($portalUser) {
                        return $portalUser;
                    }
                } catch (\Throwable $e2) {
                    continue;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal auto-discovery schema portal: ' . $e->getMessage());
        }

        return null;
    }
}
