<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengembalian extends Model
{
    use HasFactory;
    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'tanggal_pengembalian',
        'kondisi',
        'status',
        'alasan',
        'keterangan_pengembalian',
    ];
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
