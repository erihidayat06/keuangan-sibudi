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
        Schema::create('bdmuks', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->integer('nilai');
            $table->integer('wkt_ekonomis');
            $table->integer('masa_pakai')->default(null);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bdmuks');
    }
};
