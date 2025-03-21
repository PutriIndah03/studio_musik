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
        Schema::create('alat_musik', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('tipe')->nullable();
            $table->string('foto')->nullable();
            $table->integer('jumlah')->nullable();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak']);
            $table->enum('status', ['Tersedia', 'Tidak Tersedia'])->default('Tersedia'); // Status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat_musiks');
    }
};
