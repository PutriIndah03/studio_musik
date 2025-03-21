<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'studio_id',
        'alat_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'jumlah',
        'status',
        'alasan',
        'jaminan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function studio_musik()
    {
        return $this->belongsTo(studio_musik::class, 'studio_id'); 
    }

    public function alat_musik()
    {
        return $this->hasMany(alat_musik::class, 'id', 'alat_id'); // Perbaikan relasi
    }

    public function alat_musiks()
    {
        return alat_musik::whereIn('id', json_decode($this->alat_id, true))->get();
    }
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }
}
