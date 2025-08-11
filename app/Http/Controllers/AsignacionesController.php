<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignaciones;
use App\Models\actaAsignacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Jenssegers\Date\Date;
use App\Models\Responsables;
use App\Models\Unidadadmin;

class AsignacionesController extends Controller
{
    public function index(Request $request){
        try{
            $asignaciones = Asignaciones::join('actual','asignacion.codactual','=','actual.id')
                    ->select('asignacion.descripcion','asignacion.created_at')
                    ->where('asignacion.codresp', '=', $request->codresp)
                    ->where('asignacion.codofic', '=', $request->codofic)
                    ->groupBy('asignacion.descripcion','asignacion.created_at')
                    ->paginate(10);
                    return [
                            'pagination' => [
                                'total'        => $asignaciones->total(),
                                'current_page' => $asignaciones->currentPage(),
                                'per_page'     => $asignaciones->perPage(),
                                'last_page'    => $asignaciones->lastPage(),
                                'from'         => $asignaciones->firstItem(),
                                'to'           => $asignaciones->lastItem(),
                            ],
                            'asignaciones'=>$asignaciones
                            ];
        }catch(\Exception $e){
            return response()->json(['error' => 'Error al cargar las asignaciones: ' . $e->getMessage()], 500);
        }
        
    }
    public function repAsignaciones(){
        $asignaciones = Asignaciones::join('actual','asignacion.codactual','=','actual.id')
        ->join('resp',function ($join) {
            $join->on('asignacion.codresp', '=', 'resp.codresp');
                $join->on('asignacion.codofic', '=', 'resp.codofic');})
        ->join('oficina','oficina.codofic','=','resp.codofic')
        ->select('actual.id as codigo','resp.nomresp','oficina.nomofic','asignacion.usuario','asignacion.created_at')->get();
        return response()->json(['asignaciones' => $asignaciones]);      
    }
    public function actaAsignaciones(Request $request){
        $codresp = $request->codresp;
        $codofic = $request->codofic;
        $id_asig = $request->id_asig;
        $unidad = $request->unidad;
        $descripunidad = Unidadadmin::where('unidad','=',$unidad)->first();
        Date::setLocale('es');
        $fechaTitulo = Date::now()->format('l j F Y');
        $fechDerecha = Date::now()->format('d/M/Y');
    
        $datos = Asignaciones::join('actual','actual.id','=','asignacion.codactual')
                               ->join('auxiliar',function ($join) {
                                    $join->on('actual.codaux', '=', 'auxiliar.codaux');
                                    $join->on('actual.codcont', '=', 'auxiliar.codcont');
                                })
            ->join('estado','actual.codestado','=','estado.id')
            ->join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
            ->select('actual.codigo','actual.codaux','auxiliar.nomaux','estado.nomestado', 'actual.descripcion','actual.codcont')
            ->distinct()
            ->where('asignacion.id_asignacion','=',$id_asig)
            ->where('asignacion.descripcion','=',1)
            ->where('actual.unidad','=',$unidad)
            ->get();

        $total = $datos->count();

        $responsable = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
                                    ->join('unidadadmin','resp.unidad','=','unidadadmin.unidad')
                                    ->select('resp.nomresp','oficina.nomofic','resp.cargo','oficina.codofic','resp.ci','unidadadmin.descrip as unidad')
                                    ->where('resp.unidad','=',$unidad)
                                    ->where('resp.codresp','=',$codresp)
                                    ->where('resp.codofic','=',$codofic)->first();
        
        $pdf=Pdf::loadView('plantillapdf.repAsignacion',['datos'=>$datos,'responsable'=>$responsable,'fechaTitulo'=>$fechaTitulo,'fechaDerecha'=>$fechDerecha,'total'=>$total,'unidad'=>$unidad,'desuni'=>$descripunidad]);
        $pdf->set_paper(array(0,0,800,617));
        return $pdf->stream();
    }
}
