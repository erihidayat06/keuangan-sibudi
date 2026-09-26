<?php

namespace App\Console\Commands;

use App\Services\DemoSandboxService;
use Illuminate\Console\Command;

class DemoCleanupCommand extends Command
{
    protected $signature = 'demo:cleanup';
    protected $description = 'Membersihkan data transaksi dan akun praktikum demo yang telah melewati batas waktu 1 jam';

    public function handle(DemoSandboxService $sandboxService): int
    {
        $this->info('Memulai pembersihan data sesi demo kedaluwarsa...');
        $deletedCount = $sandboxService->cleanupExpiredSessions();
        $this->info("Selesai! {$deletedCount} sesi praktikum kedaluwarsa beserta seluruh datanya berhasil dibersihkan.");
        return Command::SUCCESS;
    }
}
