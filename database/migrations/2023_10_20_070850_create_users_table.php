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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->unsignedSmallInteger('id_kategori')->nullable();
            $table->string('username', 15)->unique();
            $table->string('password');
            $table->string('nama_user', 35);
            $table->date('tgl_lahir');
            $table->string('notelp', 15);
            $table->string('alamat', 30);
            $table->string('foto_profile', 100)->nullable();
            $table->string('hak_akses', 10);
            $table->boolean('is_first_login');

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
