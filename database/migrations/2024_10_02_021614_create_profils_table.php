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
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('nm_bumdes', 100);
            $table->string('desa', 100);
            $table->string('kecamatan', 100);
            $table->string('nm_direktur', 50);
            $table->string('nm_serkertaris', 50);
            $table->string('nm_bendahara', 50);
            $table->string('nm_pengawas', 50);
            $table->string('nm_penasehat', 50);
            $table->string('unt_usaha1', 50);
            $table->string('nm_kepyun1', 50);
            $table->string('unt_usaha2', 50);
            $table->string('nm_kepyun2', 50);
            $table->string('unt_usaha3', 50);
            $table->string('nm_kepyun3', 50);
            $table->string('unt_usaha4', 50);
            $table->string('nm_kepyun4', 50);
            $table->string('no_badan', 50);
            $table->string('no_perdes', 50);
            $table->string('no_sk', 50);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
