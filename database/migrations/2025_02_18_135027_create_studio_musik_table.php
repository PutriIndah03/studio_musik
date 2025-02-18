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
        Schema::create('studio_musik', function (Blueprint $table) {
            $table->id(); // Kolom ID (Auto Increment)
            $table->string('nama'); // Nama Studio Musik
            $table->text('deskripsi')->nullable(); // Deskripsi Studio Musik
            $table->string('foto')->nullable(); // Foto Studio Musik (path file)
            $table->enum('status', ['Tersedia', 'Tidak Tersedia'])->default('Tersedia'); // Status
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studio_musik');
    }
};
