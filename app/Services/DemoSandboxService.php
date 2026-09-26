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
        // 0. Pastikan migrasi kolom is_demo telah tersedia (auto-heal jika belum di-migrate di production)
        if (!Schema::hasColumn('users', 'is_demo')) {
            try {
                Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!Schema::hasColumn('users', 'is_demo')) {
                        $table->boolean('is_demo')->default(false)->after('role');
                    }
                    if (!Schema::hasColumn('users', 'demo_token')) {
                        $table->string('demo_token', 100)->nullable()->index()->after('is_demo');
                    }
                    if (!Schema::hasColumn('users', 'demo_expires_at')) {
                        $table->timestamp('demo_expires_at')->nullable()->after('demo_token');
                    }
                    if (!Schema::hasColumn('users', 'portal_user_id')) {
                        $table->unsignedBigInteger('portal_user_id')->nullable()->index()->after('demo_expires_at');
                    }
                });
            } catch (\Throwable $e) {
                Log::warning('Gagal auto-add kolom demo ke tabel users: ' . $e->getMessage());
            }
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
                'name'            => trim($portalUser->name) . ' (Admin Trial)',
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
                'name'               => trim($portalUser->name) . ' (Admin Trial)',
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
                'nama_bumdes'        => 'BUMDes ' . trim($portalUser->name),
                'alamat_bumdes'      => 'Desa Praktikum Academy',
                'nomor_hukum_bumdes' => 'AHU-0000.TRIAL.2026',
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
     * Mencari user dari database portal dengan multi-strategi:
     * 1. Koneksi sekunder 'portal' bawaan Laravel
     * 2. Direct PDO ke database portal (mencakup Hostinger cPanel dan local)
     * 3. Auto-discovery schema di instance MySQL yang sama
     */
    public function findPortalUser(string $token)
    {
        // Utamakan bumdespro_token dan bumdespro2_token
        $tokenColumns = ['bumdespro_token', 'bumdespro2_token', 'keuangan_token', 'token', 'api_token'];

        // 1. Coba koneksi sekunder 'portal' yang didefinisikan di config/database.php
        try {
            foreach ($tokenColumns as $column) {
                try {
                    $portalUser = DB::connection('portal')
                        ->table('users')
                        ->where($column, $token)
                        ->first();

                    if ($portalUser) {
                        Log::info("[PortalBUMDes SSO] User ditemukan via koneksi 'portal', kolom: {$column}");
                        return $portalUser;
                    }
                } catch (\Throwable $eCol) {
                    continue;
                }
            }
        } catch (\Throwable $e) {
            Log::info('[PortalBUMDes SSO] Koneksi portal Laravel gagal dicoba: ' . $e->getMessage());
        }

        // 2. Direct PDO kandidat: menangani kasus Hostinger hPanel/cPanel dan local
        $portalDbCandidates = [
            [
                'host'     => env('PORTAL_DB_HOST', 'localhost'),
                'database' => env('PORTAL_DB_DATABASE', 'u110981049_portal_bumbdes'),
                'username' => env('PORTAL_DB_USERNAME', 'u110981049_portal_bumbdes'),
                'password' => env('PORTAL_DB_PASSWORD', 'Portal2026!'),
            ],
            [
                'host'     => '127.0.0.1',
                'database' => 'u110981049_portal_bumbdes',
                'username' => 'u110981049_portal_bumbdes',
                'password' => 'Portal2026!',
            ],
            [
                'host'     => 'localhost',
                'database' => 'u110981049_portal_bumbdes',
                'username' => 'u110981049_portal_bumbdes',
                'password' => 'Portal2026!',
            ],
            [
                'host'     => env('PORTAL_DB_HOST', 'localhost'),
                'database' => 'portal_bumbdes',
                'username' => env('PORTAL_DB_USERNAME', env('DB_USERNAME', 'root')),
                'password' => env('PORTAL_DB_PASSWORD', env('DB_PASSWORD', 'root')),
            ],
            [
                'host'     => '127.0.0.1',
                'database' => 'portal_bumbdes',
                'username' => 'root',
                'password' => 'root',
            ],
        ];

        foreach ($portalDbCandidates as $cand) {
            try {
                $dsn = "mysql:host={$cand['host']};port=3306;dbname={$cand['database']};charset=utf8mb4";
                $pdo = new \PDO($dsn, $cand['username'], $cand['password'], [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_TIMEOUT            => 2,
                ]);

                foreach ($tokenColumns as $col) {
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM users WHERE `{$col}` = ? LIMIT 1");
                        $stmt->execute([$token]);
                        $user = $stmt->fetch();
                        if ($user) {
                            Log::info("[PortalBUMDes SSO] User ditemukan via direct PDO ({$cand['database']} @ {$cand['host']}, kolom: {$col})");
                            return $user;
                        }
                    } catch (\Throwable $eCol) {
                        continue;
                    }
                }
            } catch (\Throwable $ePdo) {
                continue;
            }
        }

        // 3. Auto-discovery schema di server MySQL yang sama (misal u110981049_portal_...)
        try {
            $databases = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%portal%'");
            foreach ($databases as $db) {
                $schemaName = $db->SCHEMA_NAME;
                foreach ($tokenColumns as $column) {
                    try {
                        $portalUser = DB::table("{$schemaName}.users")
                            ->where($column, $token)
                            ->first();

                        if ($portalUser) {
                            Log::info("[PortalBUMDes SSO] User ditemukan via auto-discovery schema: {$schemaName}, kolom: {$column}");
                            return $portalUser;
                        }
                    } catch (\Throwable $e2) {
                        continue;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[PortalBUMDes SSO] Gagal auto-discovery schema: ' . $e->getMessage());
        }

        Log::warning('[PortalBUMDes SSO] Token tidak ditemukan di semua database portal yang dicoba.');
        return null;
    }
}
