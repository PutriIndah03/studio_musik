<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    use HasFactory;
    protected $table = 'staf';
    protected $fillable = ['nim','nama', 'prodi', 'alamat', 'jenis_kelamin', 'email',
                            'no_hp', 'foto'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}