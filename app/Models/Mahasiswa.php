<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'mahasiswa';
    protected $fillable = ['nim','nama', 'prodi', 'alamat', 'jenis_kelamin', 'email',
                            'no_hp', 'foto'];

 public function users(){
    
    return $this->hasMany(User::class, 'mahasiswa_id', 'id');
    }
                            
                            
}
