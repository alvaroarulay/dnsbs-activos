<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organismo extends Model
{
    use HasFactory;
    protected $table = "organismo_fin";
    protected $fillable = [
        'gestion',
        'of',
        'des',
        'sigla', 
    ];
}
