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
        Schema::create('kas_masuk', function (Blueprint $table) {
            $table->increments('id_masuk');
            $table->integer('nominal_masuk');
            $table->date('tgl_masuk');
            $table->string('deskripsi_masuk', 80);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_masuk');
    }
};
