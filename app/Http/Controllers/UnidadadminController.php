<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad;
use App\Models\Unidadadmin;

class UnidadadminController extends Controller
{
    public function index()
    {
       
        $unidad = Unidadadmin::All();
        return [
            'unidad'=>$unidad
        ];
    }
    public function store(Request $request){
        try {
            $unidad = new Unidadadmin();
            $unidad->entidad = '0015';
            $unidad->unidad=$request->sigla;
            $unidad->descrip=$request->descripunidad;
            $unidad->ciudad='DEPTO DE '.$request->sigla;
            $unidad->estadouni=1;
            $unidad->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
         } catch (Exception $e) {
             return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
         }
    }
    public function update(Request $request){
      
        try {
            $unidad = Unidadadmin::find($request->id);
            $unidad->unidad=$request->sigla;
            $unidad->descrip=$request->descripunidad;
            $unidad->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!','id'=> $request->id]);    
         } catch (Exception $e) {
             return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
         }
    }
    public function selectUnidad(){
        $idrol = \Auth::user()->idrol;
        $unidad = \Auth::user()->unidad;
        $a = Unidadadmin::select('descrip')->where('unidad','=',$unidad)->first();
        $b = Unidadadmin::select('ciudad')->where('unidad','=',$unidad)->first();
        $titulo = $a->descrip.' - '.$b->ciudad;
        $unidad1 = Unidadadmin::select('id','unidad','descrip')->where('estadouni','=',1)->get();
        return [
            'unidad'=>$unidad1,
            'titulo' => $titulo,
            'idrol' => $idrol,
            'descripcion' => $a->descrip,
            'idunidad' => $unidad
        ];
    }
}
