<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studio_musik extends Model
{
    use HasFactory;
    protected $table = 'studio_musik';
    protected $fillable = ['nama', 'foto', 'status'];

}
