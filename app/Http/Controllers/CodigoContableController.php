<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CodigoContable;
use App\Models\Auxiliares;
use App\Models\Unidadadmin;
use App\Models\Responsables;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class CodigoContableController extends Controller
{
    public function index()
    {
       $codigos = CodigoContable::select('codcont.*')
                ->distinct()->orderBy('id','asc')
                ->get();
        return [ 'codigos' => $codigos];
    }
    public function auxiliar(Request $request){
        if(isset($request->id)){
            $unidad = Unidadadmin::where('estado','=','1')->first();
            $auxiliares= Auxiliares::where([
                ['codcont', '=', $request->id],
                ['unidad', '=', $unidad->unidad],
            ])->get();
            $codconts = CodigoContable::where('codcont','=', $request->id)->first();
            return response()->json(
                [
                    'auxiliares'=>$auxiliares,
                    'codconts'=>$codconts,
                    'unidad' => $unidad,
                    'success' => true
                ]
                );
        }else
        {
            return response()->json(
                [
                   'success' => false,
                ]
                );
        }
    }
    public function update(Request $request)
    {
          try {
            $codcont = CodigoContable::findOrFail($request->id);
            $codcont->codigo=$request->codigo;
            $codcont->observ=$request->observ;
            $codcont->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
         } catch (Exception $e) {
             return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
         }
    }
}
