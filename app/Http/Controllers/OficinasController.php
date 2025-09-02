<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Oficinas;
use App\Models\Actual;
use App\Models\Responsables;
use App\Models\Unidadadmin;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;

class OficinasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $unidad = Unidadadmin::where('id','=',$request->idunidad)->first();
        $query  = Oficinas::select('oficina.id','oficina.codofic','oficina.nomofic','oficina.api_estado','oficina.observ')->where('oficina.unidad','=',$unidad->unidad);

        if ($buscar==''){
            $oficinas = $query->paginate(10);
        }
        else{
            $oficinas = $query->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')
            ->paginate(10);
        }
        return [
            'pagination' => [
                'total'        => $oficinas->total(),
                'current_page' => $oficinas->currentPage(),
                'per_page'     => $oficinas->perPage(),
                'last_page'    => $oficinas->lastPage(),
                'from'         => $oficinas->firstItem(),
                'to'           => $oficinas->lastItem(),
            ],
            'oficinas' => $oficinas
        ];
    }
    public function oficinas()
    {
        $oficinas = Oficinas::all();
        return ['oficinas' => $oficinas];
        
    }
    public function oficinasactivos($unidad){
        return response()->json(Oficinas::where('unidad','=',$unidad)->get());
    }
    public function store(Request $request){
        $unidad = Unidadadmin::where('id','=',$request->idunidad)->first();
        $ofic = Oficinas::where('unidad','=',$unidad->unidad)->count();
        try {
            $oficina = new Oficinas();
            $oficina->entidad = "0015";
            $oficina->unidad = $unidad->unidad;
            $oficina->idestablecimiento = $request->idedif;
            $oficina->codofic = $ofic + 1;
            $oficina->nomofic = $request->nomofic;
            $oficina->observ = $request->observ;
            $oficina->feult = Carbon::now()->format('d/m/y');
            $oficina->usuar = auth()->user()->username;
            $oficina->api_estado = $request->estado;
            $oficina->estadodbf = 1;
            $oficina->save();
            return response()->json(['message' => 'Datos guardados correctamente!!!','idedif'=>$oficina->idestablecimiento,'codofic'=>$oficina->codofic]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
    }
    public function update(Request $request){
        try {
            $ofic = Oficinas::where('id','=',$request->id)->first();
            $oficina = Oficinas::findOrFail($request->id);
            $oficina->nomofic = $request->nomofic;
            $oficina->observ = $request->observ;
            $oficina->usuar = auth()->user()->username;
            $oficina->api_estado = $request->estado;
            $oficina->estadodbf = 1;
            $oficina->save();
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!','idedif'=>$ofic->idestablecimiento,'codofic'=>$ofi->codofic]);
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
    }
    public function responsables(Request $request){
        if(isset($request->id)){
            $unidad = Unidadadmin::where('estado','=','1')->first();
            $responsables= Responsables::where([
                ['codofic', '=', $request->id],
                ['unidad', '=', $unidad->unidad],
            ])->get();
            $oficinas = Oficinas::where('codofic','=', $request->id)->first();
            return response()->json(
                [
                    'responsables'=>$responsables,
                    'oficinas'=>$oficinas,
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
    public function responsableActuales(Request $request){
        if(isset($request->id)){
            $unidad = Unidadadmin::where('estado','=','1')->first();
            $actuales = Actual::where('unidad','=',$unidad->unidad)->get();
            $responsables= Responsables::where([
                ['codofic', '=', $request->id],
                ['unidad', '=', $unidad->unidad],
            ])->get();
            $oficinas = Oficinas::where('codofic','=', $request->id)->first();
            return response()->json(
                [
                    'responsables'=>$responsables,
                    'oficinas'=>$oficinas,
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
    public function buscarOficina(Request $request){

        //if (!$request->ajax()) return redirect('/');
        $edif = $request->edif;
        $filtro = $request->filtro;
        $unidad = Unidadadmin::where('id','=',$request->idunidad)->first();
        $oficina = Oficinas::where('unidad','=',$unidad->unidad)->where('codofic','=', $filtro)->first();
        return response()->json(['oficina' => $oficina]);
    }
    
    public function activar(Request $request){
        try {

            $oficina = Oficinas::findOrFail($request->id);
            $oficina->api_estado = 1;
            $oficina->estadodbf = 1;
            $oficina->save();

            return response()->json(['message' => 'Oficina activada!!!','id'=>$oficina->id]);

        } catch (Exception $e) {

            return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
    }
    public function desactivar(Request $request){
        try {

            $oficina = Oficinas::findOrFail($request->id);
            $oficina->api_estado = 0;
            $oficina->estadodbf = 1;
            $oficina->save();

            return response()->json(['message' => 'Oficina desactivada!!!','id'=>$oficina->id]);

        } catch (Exception $e) {

            return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }
    }
}
