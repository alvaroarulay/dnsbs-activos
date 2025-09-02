<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auxiliares;
use App\Models\Unidadadmin;
use App\Models\Responsables;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;

class AuxiliaresController extends Controller
{
   
    public function index(Request $request)
    {
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
            $totalaux = Auxiliares::where('auxiliar.codcont','=',$request->codcont)->where('auxiliar.unidad', '=', 'DSNAL')->count();
            $query= Auxiliares::select('auxiliar.id','auxiliar.codaux','auxiliar.nomaux')
            ->distinct()
            ->where('auxiliar.codcont','=',$request->codcont)->where('auxiliar.unidad', '=', 'DSNAL');
            if ($buscar==''){
                $auxiliares = $query->orderBy('id', 'asc')->get();
            }
            else{
                $auxiliares = $query->where('auxiliar.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'asc')->get();
            }
            return [
                'auxiliares' => $auxiliares,
                'totalaux' => $totalaux
            ];
    }
    public function store(Request $request){
        $unidad = Unidadadmin::all();
        $codaux = Auxiliares::where('codcont', '=', $request->codcont)
            ->where('unidad', '=', 'DSNAL')
            ->count();
        try {
            foreach ($unidad as $uni) {
                $auxiliar = new Auxiliares();
                $auxiliar->entidad = '0015';
                $auxiliar->unidad = $uni->unidad;
                $auxiliar->codcont = $request->codcont;
                $auxiliar->codaux = $codaux + 1;
                $auxiliar->nomaux = $request->nomaux;
                $auxiliar->observ = $request->observ;
                $auxiliar->usuar = auth()->user()->username;
                $auxiliar->estadodbf = 1;
                
                $auxiliar->save();
            }
            
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
        
        return response()->json(['message' => 'Datos Guardados Correctamente!!!']);
    }
    public function selectAuxiliar($id){
       
        $unidad = Unidadadmin::where('estado','=','1')->first();

        $auxiliares = Auxiliares::select('id','nomaux','codaux')
            ->where('codcont','=',$id)
            ->get();
        return response()->json(['auxiliares'=>$auxiliares]);
    }
    public function update(Request $request){
        $unidad = Unidadadmin::all();
        try {
            foreach ($unidad as $uni) {
                $auxiliar = Auxiliares::where('codcont', '=', $request->codcont)->where('codaux', '=', $request->codaux)->where('unidad', '=', $uni->unidad)
                    ->update([
                        'nomaux' => $request->nomaux,
                        'observ' => $request->observ,
                        'usuar' => auth()->user()->username,
                        'estadodbf' => 1
                    ]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
        
        return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
    }
}
