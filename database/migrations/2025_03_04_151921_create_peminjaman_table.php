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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained('studio_musik')->onDelete('cascade');
            $table->text('alat_id'); 
            $table->datetime('tanggal_pinjam');
            $table->datetime('tanggal_kembali');
            $table->integer('jumlah')->nullable();
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Dikembalikan'])->default('Menunggu');
            $table->text('alasan')->nullable();
            $table->enum('jaminan', ['KTP', 'KTM']);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
