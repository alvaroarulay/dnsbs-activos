<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory; 
    protected $table = "cla_depts";
    protected $fillable = [
        'id',
        'codigo',
        'desc',
        'sigla', 
    ];
}