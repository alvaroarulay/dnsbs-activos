<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unidadadmin;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class CodigoContable extends Model
{
    use HasFactory;
    protected $table = "codcont";
    protected $fillable = [
        'id',
        'codigo',
        'codcont',
        'nombre',
        'vidautil',
        'observ', 
        'depreciar', 
        'actualizar', 
        'feult',
        'usuar',
    ];
}           
