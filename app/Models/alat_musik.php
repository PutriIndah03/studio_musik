<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class alat_musik extends Model
{
    use HasFactory;
    protected $table = 'alat_musik';
    protected $fillable = ['kode', 'nama','tipe', 'foto', 'jumlah', 'kondisi', 'status'];

    public function peminjaman()
    {
        return $this->hasMany(peminjaman::class);
    }
}
