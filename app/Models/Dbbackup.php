<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dbbackup extends Model
{
    use HasFactory;
     protected $table = "dbbackup";
    protected $fillable = [
        'id',
        'archivo',
        'usuar',
    ];
}
