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
        Schema::create('tragets', function (Blueprint $table) {
            $table->id();
            $table->text('omset');
            $table->text('pembiayaan');
            $table->int('laba');
            $table->int('aset');
            $table->int('pades');
            $table->year('tahun');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tragets');
    }
};
