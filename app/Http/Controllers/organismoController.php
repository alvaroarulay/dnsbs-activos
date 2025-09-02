<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\organismo;

class organismoController extends Controller
{
    public function organismo()
    {
        $organismos = organismo::all();
        return ['organismos' => $organismos];
        
    }
}
