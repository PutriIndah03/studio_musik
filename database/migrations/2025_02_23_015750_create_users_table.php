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
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id')->nullable(); // Mahasiswa ID (nullable jika user bukan mahasiswa)
            $table->unsignedBigInteger('staf_id')->nullable(); // Staf ID (nullable jika user bukan staf)
            $table->string('username')->unique(); // Username unik (bisa pakai NIM)
            $table->string('password'); // Password user
            $table->enum('role', ['pembina', 'staf', 'mahasiswa'])->default('mahasiswa'); // Role dengan default 'mahasiswa'
            $table->timestamps(); // Perbaikan penulisan
        
            // Foreign key constraints
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('staf_id')->references('id')->on('staf')->onDelete('cascade');
        });
        

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
