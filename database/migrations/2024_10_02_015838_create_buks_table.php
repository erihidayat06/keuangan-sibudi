<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buks', function (Blueprint $table) {
            $table->id();
            $table->string('transaksi', 100);
            $table->string('jenis', 100);
            $table->enum('jenis_dana', ['operasional', 'iventasi', 'pendanaan'])->default('operasional');
            $table->enum('jenis_lr', ['pu1', 'pu2', 'pu3', 'pu4', 'bo1', 'bo2', 'bo3', 'bo4', 'bno1', 'bno2', 'bno3', 'bno4', 'kas']);
            $table->integer('nilai');
            $table->date('tanggal');
            $table->string('akun', 100);
            $table->integer('id_akun');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buks');
    }
};
