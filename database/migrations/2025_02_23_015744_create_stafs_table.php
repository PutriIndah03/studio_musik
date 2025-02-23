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
        Schema::create('staf', function (Blueprint $table) {
            $table->id(); // Primary Key (Auto Increment)
            $table->string('nim')->unique(); // NIM sebagai identifier unik
            $table->string('nama'); // Nama Mahasiswa
            $table->string('prodi'); // Program Studi
            $table->text('alamat')->nullable(); // Alamat (Bisa kosong)
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(); // Jenis Kelamin
            $table->string('email')->unique(); // Email Unik
            $table->string('no_hp')->nullable(); // Nomor HP (Bisa kosong)
            $table->string('foto')->nullable(); // Foto Profil (Bisa kosong)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafs');
    }
};
