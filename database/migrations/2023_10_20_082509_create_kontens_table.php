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
        Schema::create('konten', function (Blueprint $table) {
            $table->increments('id_konten');
            $table->unsignedInteger('id_user');
            $table->string('judul_konten', 50);
            $table->longText('isi_konten');
            $table->string('gambar', 100);
            $table->date('tgl_konten');

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
