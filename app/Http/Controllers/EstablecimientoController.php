<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establecimiento;

class EstablecimientoController extends Controller
{
    public function index($id){
        $establecimientos = Establecimiento::where('idunidad','=',$id)->get();
        return [
            'establecimientos'=>$establecimientos,
        ];
    }
    public function store(Request $request){
        try {
            $establecimiento = new Establecimiento();
            $establecimiento->sigla=$request->sigla;
            $establecimiento->descripcion=$request->descripestab;
            $establecimiento->idunidad=$request->idunidad;
            $establecimiento->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!','id'=> $establecimiento->id]);
         } catch (Exception $e) {
             return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
         }
    }
    public function update(Request $request){
      
        try {
            $establecimiento = Establecimiento::find($request->id);
            $establecimiento->sigla=$request->sigla;
            $establecimiento->descripcion=$request->descripestab;
            $establecimiento->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!','id'=> $request->id]);    
         } catch (Exception $e) {
             return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
         }
    }
}
