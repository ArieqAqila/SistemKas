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
        Schema::create('kas_keluar', function (Blueprint $table) {
            $table->increments('id_keluar');
            $table->integer('nominal_keluar');
            $table->date('tgl_keluar');
            $table->string('deskripsi_keluar', 80);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_keluar');
    }
};
