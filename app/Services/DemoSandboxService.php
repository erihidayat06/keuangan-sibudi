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
     * Normalisasi nilai referral:
     * 1 = BUMDesa
     * 0 = Koperasi (BUMDes Bersama)
     */
    public function normalizeReferral($referral = null, $portalUser = null): int
    {
        if ($referral !== null && $referral !== '') {
            $str = strtolower(trim((string) $referral));
            if (in_array($str, ['1', 'true', 'bumdes', 'bumdesa', 'bumdes_desa'], true)) {
                return 1;
            }
            if (in_array($str, ['0', 'false', 'koperasi', 'bumdes-bersama', 'bumdes_bersama', 'bersama'], true)) {
                return 0;
            }
            return ((int) $referral === 1) ? 1 : 0;
        }

        if ($portalUser && isset($portalUser->referral)) {
            return ((int) $portalUser->referral === 1) ? 1 : 0;
        }

        // Default sesuai sistem praktikum saat ini (0 = Koperasi)
        return 0;
    }

    /**
     * Pemisah antara token portal dan device key di kolom users.demo_token.
     */
    public const DEVICE_SEPARATOR = '::';

    /**
     * Ubah device ID mentah (dari localStorage browser) menjadi device key pendek yang aman.
     * Mengembalikan null jika device ID tidak valid / tidak tersedia.
     */
    public function makeDeviceKey(?string $rawDeviceId): ?string
    {
        $rawDeviceId = trim((string) $rawDeviceId);
        if ($rawDeviceId === '' || !preg_match('/^[A-Za-z0-9\-]{8,64}$/', $rawDeviceId)) {
            return null;
        }

        return substr(sha1($rawDeviceId), 0, 12);
    }

    /**
     * Gabungkan token portal + device key menjadi kunci sesi yang unik per perangkat.
     */
    public function buildDeviceScopedToken(string $token, ?string $deviceKey): string
    {
        if (empty($deviceKey)) {
            return $token;
        }

        // Kolom demo_token maksimal 100 karakter
        $maxTokenLen = 100 - strlen(self::DEVICE_SEPARATOR) - strlen($deviceKey);

        return substr($token, 0, $maxTokenLen) . self::DEVICE_SEPARATOR . $deviceKey;
    }

    /**
     * Pecah demo_token tersimpan menjadi [token portal, device key|null].
     */
    public function splitDeviceScopedToken(string $storedToken): array
    {
        $pos = strrpos($storedToken, self::DEVICE_SEPARATOR);
        if ($pos === false) {
            return [$storedToken, null];
        }

        $token = substr($storedToken, 0, $pos);
        $deviceKey = substr($storedToken, $pos + strlen(self::DEVICE_SEPARATOR));

        return [$token, $deviceKey !== '' ? $deviceKey : null];
    }

    /**
     * Authenticate or create a demo user using the portal token.
     * Mendukung referral: 1 = BUMDesa, 0 = Koperasi.
     * Jika $deviceKey diberikan, data praktikum dipisah per perangkat (berdasarkan localStorage browser),
     * sehingga token yang sama di laptop berbeda akan mendapatkan data yang berbeda.
     */
    public function authenticateByToken(string $token, $referral = null, ?string $customName = null, ?string $deviceKey = null): ?User
    {
        $sessionToken = $this->buildDeviceScopedToken($token, $deviceKey);

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

        // BEST PRACTICE FALLBACK:
        // Jika token tidak ditemukan di database portal (misal user baru belum generate token di portal,
        // atau sedang uji coba/trial langsung via URL dengan token custom/bebas):
        // Jangan gagal/error! Buatkan Virtual Portal User on-the-fly agar praktikum TETAP BISA BERJALAN.
        if (!$portalUser) {
            $cleanToken = preg_replace('/[^a-zA-Z0-9_\-]/', '', $token);
            $tokenSuffix = substr(strtoupper(md5($token)), 0, 4);

            if (!empty($customName)) {
                $displayName = trim($customName);
            } elseif (!empty($cleanToken) && strlen($cleanToken) <= 20 && !is_numeric($cleanToken)) {
                $displayName = ucfirst($cleanToken);
            } else {
                $displayName = 'Peserta ' . $tokenSuffix;
            }

            $portalUser = (object) [
                'id'       => abs(crc32($token)),
                'name'     => $displayName,
                'email'    => 'demo_' . strtolower($tokenSuffix) . '@academy.portal',
                'referral' => $referral,
            ];

            Log::info("[PortalBUMDes SSO] Token '{$token}' tidak ada di portal DB. Dibuatkan Virtual User '{$displayName}' on-the-fly.");
        }

        // Tentukan nilai referral: 1 = BUMDesa, 0 = Koperasi
        $referralVal = $this->normalizeReferral($referral, $portalUser);
        $isKoperasi = ($referralVal === 0);
        $entityType = $isKoperasi ? 'Koperasi' : 'BUMDesa';
        $entityName = ($isKoperasi ? 'Koperasi Praktikum ' : 'BUMDes Praktikum ') . $portalUser->name;
        $alamatBumdes = $isKoperasi ? 'Koperasi Praktikum Academy' : 'Desa Praktikum Academy';
        $nomorHukum = $isKoperasi ? 'AHU-0000.KOPERASI.2026' : 'AHU-0000.PRAKTIKUM.2026';

        // 2. Cek apakah user demo sudah ada untuk token (per perangkat) DAN jenis referral ini.
        //    Jika device key tersedia, JANGAN cocokkan via portal_user_id agar data antar perangkat tidak tercampur.
        $user = User::where('is_demo', true)
            ->where('referral', $referralVal)
            ->where(function ($query) use ($sessionToken, $portalUser, $deviceKey) {
                $query->where('demo_token', $sessionToken);
                if (empty($deviceKey) && !empty($portalUser->id)) {
                    $query->orWhere('portal_user_id', $portalUser->id);
                }
            })
            ->first();

        if ($user) {
            $isExpired = $user->demo_expires_at && now()->greaterThan($user->demo_expires_at);

            // Bersihkan data transaksi jika sesi habis (> 1 jam)
            if ($isExpired) {
                $this->purgeUserData($user->id);
            }

            // Perbarui token dan perpanjang masa aktif 1 jam
            $user->update([
                'name'               => "[Praktikum {$entityType}] " . $portalUser->name,
                'demo_token'         => $sessionToken,
                'portal_user_id'     => $portalUser->id,
                'demo_expires_at'    => now()->addHour(),
                'status'             => 1,
                'tgl_langganan'      => now()->addDays(30),
                'role'               => 'bumdes',
                'referral'           => $referralVal,
                'user_roles_id'      => $user->user_roles_id ?: 2,
                'nama_bumdes'        => $entityName,
                'alamat_bumdes'      => $alamatBumdes,
                'nomor_hukum_bumdes' => $nomorHukum,
            ]);
        } else {
            // 3. Buat user demo baru terisolasi khusus untuk jenis referral ini
            $email = 'demo_' . $portalUser->id . '_' . ($referralVal ? 'bumdes' : 'koperasi') . '_' . Str::random(4) . '@academy.portal';

            $user = User::create([
                'name'               => "[Praktikum {$entityType}] " . $portalUser->name,
                'email'              => $email,
                'password'           => bcrypt(Str::random(16)),
                'role'               => 'bumdes',
                'referral'           => $referralVal,
                'status'             => 1,
                'tgl_langganan'      => now()->addDays(30),
                'user_roles_id'      => 2,
                'is_demo'            => true,
                'demo_token'         => $sessionToken,
                'portal_user_id'     => $portalUser->id,
                'demo_expires_at'    => now()->addHour(),
                'nama_bumdes'        => $entityName,
                'alamat_bumdes'      => $alamatBumdes,
                'nomor_hukum_bumdes' => $nomorHukum,
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
        $isKoperasi = ((int) $user->referral === 0);
        $entityName = $user->nama_bumdes ?: ($isKoperasi ? 'Koperasi Praktikum' : 'BUMDes Praktikum');
        $nomorHukum = $user->nomor_hukum_bumdes ?: ($isKoperasi ? 'AHU-0000.KOPERASI.2026' : 'AHU-0000.PRAKTIKUM.2026');

        if (class_exists(\App\Models\Ekuit::class)) {
            \App\Models\Ekuit::firstOrCreate(['user_id' => $userId]);
        }

        if (class_exists(\App\Models\Profil::class)) {
            $profil = \App\Models\Profil::where('user_id', $userId)->first();
            if (!$profil) {
                \App\Models\Profil::create([
                    'user_id'       => $userId,
                    'nm_bumdes'     => $entityName,
                    'desa'          => 'Desa Praktikum',
                    'kecamatan'     => 'Kecamatan Academy',
                    'kabupaten'     => 'Kabupaten Praktikum',
                    'no_badan'      => $nomorHukum,
                    'nm_direktur'   => 'Direktur ' . ($isKoperasi ? 'Koperasi' : 'BUMDes'),
                    'nm_bendahara'  => 'Bendahara ' . ($isKoperasi ? 'Koperasi' : 'BUMDes'),
                    'nm_pengawas'   => 'Pengawas ' . ($isKoperasi ? 'Koperasi' : 'BUMDes'),
                    'nm_penasehat'  => 'Penasehat ' . ($isKoperasi ? 'Koperasi' : 'BUMDes'),
                ]);
            } else {
                $profil->update([
                    'nm_bumdes'     => $entityName,
                    'no_badan'      => $nomorHukum,
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
