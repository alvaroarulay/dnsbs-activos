<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Actual;
use App\Models\CodigoContable;
use App\Models\Auxiliares;
use App\Models\Oficinas;
use App\Models\Responsables;
use App\Models\Unidadadmin;
use App\Models\Ciudad;
use App\Models\Establecimiento;
use App\Models\Logs;
use App\Models\Asignaciones;
use App\Models\actaAsignacion;
use App\Models\actaDevolucion;
use Barryvdh\DomPDF\Facade\Pdf;
use Jenssegers\Date\Date;

class ActualController extends Controller
{
    public function index(Request $request)
{   
    $buscar = $request->buscar;
    $criterio = $request->criterio;
    $unidadv = $request->unidad;
    $idrol = \Auth::user()->idrol;
    $unidad = \Auth::user()->unidad;
    $query = Actual::join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
                ->join('oficina', function ($join) {
                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                    $join->on('actual.codofic', '=', 'oficina.codofic');
                })
                ->join('resp', function ($join) {
                            $join->on('unidadadmin.unidad', '=', 'resp.unidad');
                            $join->on('resp.codofic', '=', 'oficina.codofic');
                            $join->on('actual.codresp', '=', 'resp.codresp');
                            $join->on('actual.codofic', '=', 'resp.codofic');
                        })
                ->join('codcont','actual.codcont','=','codcont.codcont')
                ->join('auxiliar', function ($join) {
                    $join->on('unidadadmin.unidad', '=', 'auxiliar.unidad');
                    $join->on('auxiliar.codcont', '=', 'codcont.codcont');
                    $join->on('actual.codaux', '=', 'auxiliar.codaux');
                    $join->on('actual.codcont', '=', 'auxiliar.codcont');
                })
                ->select('actual.*', 'codcont.nombre','auxiliar.nomaux', 'oficina.nomofic', 'resp.nomresp');
    if($idrol == 1){
        if($unidadv == ''){
            //usar unidad
            $a = Unidadadmin::select('descrip')->where('unidad','=',$unidad)->first();
            $b = Unidadadmin::select('ciudad')->where('unidad','=',$unidad)->first();
            $titulo = $a->descrip.' - '.$b->ciudad;
            $total = Actual::where('unidad','=',$unidad)->get();

            if($buscar == ''){
                $actuales = $query->where('unidadadmin.unidad','=',$unidad)->distinct()->paginate(10);
            }else{
                $actuales = $query->where('unidadadmin.unidad','=',$unidad)->where('actual.'.$criterio, 'like', '%'. $buscar . '%')
                ->distinct()
                ->paginate(10);
            }
        }else{
            // usar unidadv
            $a = Unidadadmin::select('descrip')->where('unidad','=',$unidadv)->first();
            $b = Unidadadmin::select('ciudad')->where('unidad','=',$unidadv)->first();
            $titulo = $a->descrip.' - '.$b->ciudad;
            $total = Actual::where('unidad','=',$unidadv)->get();
            if($buscar == ''){
                $actuales =$query->where('unidadadmin.unidad','=',$unidadv)->distinct()->paginate(10);
            }else{
                $actuales = $query->where('actual.'.$criterio, 'like', '%'. $buscar . '%')->distinct()->paginate(10);
            }
        }
    }else{
        $a = Unidadadmin::select('descrip')->where('unidad','=',$unidad)->first();
        $b = Unidadadmin::select('ciudad')->where('unidad','=',$unidad)->first();
        $titulo = $a->descrip.' - '.$b->ciudad;
        $total = Actual::where('unidad','=',$unidad)->get();
        if($buscar == '')
        {
            $actuales = $query->where('unidadadmin.unidad','=',$unidad)->distinct()->paginate(10);
        }
        else
        {
            $actuales = $query->where('unidadadmin.unidad','=',$unidad)->where('actual.'.$criterio, 'like', '%'. $buscar . '%')->distinct()->paginate(10);
            
        }
    }
    return [
                'pagination' => [
                    'total'        => $actuales->total(),
                    'current_page' => $actuales->currentPage(),
                    'per_page'     => $actuales->perPage(),
                    'last_page'    => $actuales->lastPage(),
                    'from'         => $actuales->firstItem(),
                    'to'           => $actuales->lastItem(),
                ],
                'actuales'=>$actuales,
                'total'=>$total->count(),
                'titulo'=>$titulo,
                'idrol'=>$idrol
                ];
    }
    public function store(Request $request)
    {
        try{
            $unidad = Unidadadmin::where('unidad','=',$request->unidad)->first();
            $oficina = Responsables::where('unidad','=',$unidad->unidad)->where('codresp','=',1)->first();
            $vidautil = CodigoContable::where('codcont', '=', $request->codcont)->first();
            $cantidad=$request->cantidad;
            $fecha = $request->fechaIncorporacion;
            $fechaJenssegers = Date::parse($fecha);

            $dia = $fechaJenssegers->day;
            $mes = $fechaJenssegers->month;
            $anio = $fechaJenssegers->year;

            if($cantidad == 1){
                if($request->correlativo<10){
                    $newcorrelativo = '000'. ($request->correlativo + 1);
                }elseif($request->correlativo<100){
                    $newcorrelativo = '00'. ($request->correlativo + 1);
                }elseif($request->correlativo<1000){
                    $newcorrelativo = '0'. ($request->correlativo + 1);
                }else{
                    $newcorrelativo = ($request->correlativo + 1);
                }
                    $actual = new Actual();
                    $actual->unidad = $request->unidad;
                    $actual->entidad = '0015';
                    $actual->codigo = $request->codigoparcial.'-'.$newcorrelativo;
                    $actual->codcont = $request->codcont;
                    $actual->codaux = $request->codaux;
                    $actual->vidautil = $vidautil->vidautil;
                    $actual->descripcion = $request->descripcion;
                    $actual->costo = $request->costo;
                    $actual->depacu = 0;
                    $actual->mes = $mes;
                    $actual->año = $anio;
                    $actual->b_rev = 0;
                    $actual->dia = $dia;
                    $actual->codofic = $request->codofic;
                    $actual->codresp = $request->codresp;
                    $actual->observ = $request->observacion;
                    $actual->dia_ant = 0;
                    $actual->mes_ant = 0;
                    $actual->año_ant = 0;
                    $actual->vut_ant = 0;
                    $actual->costo_ant = 0;
                    $actual->band_ufv = 0;
                    $actual->codestado = $request->estado;
                    $actual->cod_rube = '';
                    $actual->nro_conv = '';
                    $actual->banderas=1;
                    $actual->org_fin = $request->organismoFinanciador;
                    $actual->usuar = auth()->user()->username;
                    $actual->api_estado = 3;
                    $actual->codigosec = '';
                    $actual->estadoasignacion = 0;
                    $actual->estadodbf = 1;
                    $actual->save();
                }else{
                    for($i=0;$i<$cantidad;$i++) {
                        if($request->correlativo<10){
                            $newcorrelativo = '0000'. ($request->correlativo + $i);
                        }elseif($request->correlativo<100){
                            $newcorrelativo = '000'. ($request->correlativo + $i);
                        }elseif($request->correlativo<1000){
                            $newcorrelativo = '00'. ($request->correlativo + $i);
                        }else{
                            $newcorrelativo = '0'.($request->correlativo + $i);
                        }
                        $actual = new Actual();
                        $actual->unidad = $request->unidad;
                        $actual->entidad = '0015';
                        $actual->codigo = $request->codigoparcial.'-'.$newcorrelativo;
                        $actual->codcont = $request->codcont;
                        $actual->codaux = $request->codaux;
                        $actual->vidautil = $vidautil->vidautil;
                        $actual->descripcion = $request->descripcion;
                        $actual->costo = $request->costo;
                        $actual->depacu = 0;
                        $actual->mes = $mes;
                        $actual->año = $anio;
                        $actual->b_rev = 0;
                        $actual->dia = $dia;
                        $actual->codofic = $request->codofic;
                        $actual->codresp = $request->codresp;
                        $actual->observ = $request->observacion;
                        $actual->dia_ant = 0;
                        $actual->mes_ant = 0;
                        $actual->año_ant = 0;
                        $actual->vut_ant = 0;
                        $actual->costo_ant = 0;
                        $actual->band_ufv = 0;
                        $actual->codestado = $request->estado;
                        $actual->cod_rube = '';
                        $actual->nro_conv = '';
                        $actual->banderas=1;
                        $actual->org_fin = $request->organismoFinanciador;
                        $actual->usuar = auth()->user()->username;
                        $actual->api_estado = 3;
                        $actual->codigosec = '';
                        $actual->estadoasignacion = 0;
                        $actual->estadodbf = 1;
                        $actual->save();
                    }
                }
            return response()->json(['message'=>'Datos guardados exitosamente!']);
        }catch(Exception $e){
            return response()->json(['message' => 'Excepción capturada: '.$e->getMessage()]);
        }
    } 
    public function update(Request $request)
    {
        try {
            $articuloant = Actual::where('id','=',$request->id)->first();
            $codActualizar = $request->id;
            $contcodaux = $articuloant->codaux != $request->codaux ? true : false;
            $contdescripcion = $articuloant->descripcion != $request->descripcion ? true : false;
            $contobserv = $articuloant->observ != $request->observacion ? true : false;
            $contcodestado = $articuloant->codestado != $request->estado ? true : false;
            $contcodsec = $articuloant->codigosec != $request->codsec ? true : false;

            $articulo = Actual::findOrFail($request->id);
            $articulo->codaux = $request->codaux;
            $articulo->descripcion = $request->descripcion;
            $articulo->observ = $request->observacion;
            $articulo->codestado = $request->estado;
            $articulo->codigosec = $request->codsec;
            $articulo->usu_mod = \Auth::user()->username;
            $articulo->estadodbf = 1;
            $articulo->save();

            if ($contcodaux){
            $logs = new Logs();
            $logs->codactual = $request->id;
            $logs->descripcion = 'Se Modifico el Auxliar';
            $logs->user = auth()->user()->name;
            $logs->save();
            };
            if ($contdescripcion){
            $logs = new Logs();
            $logs->codactual = $request->id;
            $logs->descripcion = 'Se Modifico la Descripción del Activo';
            $logs->user = auth()->user()->name;
            $logs->save();
            };
            if ($contobserv){
            $logs = new Logs();
            $logs->codactual = $request->id;
            $logs->descripcion = 'Se Modifico la observación del Activo';
            $logs->user = auth()->user()->name;
            $logs->save();
            };
            if ($contcodestado){
            $logs = new Logs();
            $logs->codactual = $request->id;
            $logs->descripcion = 'Se Modifico el Estado del Activo';
            $logs->user = auth()->user()->name;
            $logs->save();
            };
            if ($contcodsec){
            $logs = new Logs();
            $logs->codactual = $request->id;
            $logs->descripcion = 'Se Modifico el código Secundario';
            $logs->user = auth()->user()->name;
            $logs->save();
            };
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '.$e->getMessage()]);
        }
        
    }
    public function updateResponasable(Request $request){
        $data = $request->data;
        $codoficina = \Auth::user()->codofic;
        $codresponsable = \Auth::user()->codresp;
        try {
            for ($i=0; $i < count($data); $i++) {

                $id = $data[$i]['id'];

                $articuloant = Actual::where('id','=',$id)->first();

                $asignacion = New Asignaciones();
                $asignacion->codactual = $id;
                $asignacion->codresp = $articuloant->codresp ;
                $asignacion->codofic = $articuloant->codofic;
                $asignacion->usuario = \Auth::user()->name;
                $asignacion->save();
                        
                $articulo = Actual::findOrFail($id);
                $articulo->codresp = $codresponsable;
                $articulo->codofic = $codoficina;
                $articulo->estadoasignacion = 0;
                $articulo->estadodbf = 1;
                $articulo->save();
                
                $logs = new Logs();
                $logs->codactual = $id;
                $logs->descripcion = 'Se Modifico el Responsable y Oficina';
                $logs->user = \Auth::user()->name;
                $logs->save();
            }
            
            } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '.$e->getMessage()]);
            }
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
    }

    public function reporteActivos()
    {   
        $idrol = \Auth::user()->idrol;
        $query = Actual::join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('actual.codofic', '=', 'oficina.codofic');
                                })
                                ->join('resp', function ($join) {
                                            $join->on('unidadadmin.unidad', '=', 'resp.unidad');
                                            $join->on('resp.codofic', '=', 'oficina.codofic');
                                            $join->on('actual.codresp', '=', 'resp.codresp');
                                            $join->on('actual.codofic', '=', 'resp.codofic');
                                        })
                                ->join('codcont','actual.codcont','=','codcont.codcont')
                                ->join('auxiliar', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'auxiliar.unidad');
                                    $join->on('auxiliar.codcont', '=', 'codcont.codcont');
                                    $join->on('actual.codaux', '=', 'auxiliar.codaux');
                                    $join->on('actual.codcont', '=', 'auxiliar.codcont');
                                })
                                ->join('estado','actual.codestado','=','estado.codestado')
                                ->select('actual.id','actual.unidad','actual.codigo','codcont.nombre',
                                'auxiliar.nomaux','actual.vidautil','oficina.nomofic','resp.nomresp',
                                'actual.descripcion','estado.nomestado','actual.estadoasignacion',
                                'actual.codigosec','actual.observ','actual.codcont','actual.codaux');
        if($idrol == 1){
            $actuales = $query->distinct()->get();
        }else{
            $actuales = $query->where('actual.unidad','=',\Auth::user()->unidad)->distinct()->get();  
        }
        return response()->json(['actuales'=>$actuales]);
    }
    public function buscarActivos(Request $request){
        $data = $request->filtro;
       $actuales = Actual::join('oficina',function($j){
            $j->on('actual.codofic', '=', 'oficina.codofic');
            $j->on('actual.unidad', '=', 'oficina.unidad');
        })
        ->join('resp', function ($join) {
                    $join->on('resp.unidad','=', 'oficina.unidad');
                    $join->on('resp.codofic', '=', 'oficina.codofic');
                    $join->on('actual.codresp', '=', 'resp.codresp');
                    $join->on('actual.codofic', '=', 'resp.codofic');
                })
        ->select('actual.*', 'oficina.nomofic', 'resp.nomresp')
        ->where('actual.codigo','=',$data)->get();
        return response()->json(['actuales'=>$actuales]);
    }
    public function buscarActivoResp(Request $request){
        $codresp = $request->codresp;
        $codofic = $request->codofic;
        $unidad = $request->unidad;
        $actuales = Actual::where('actual.codresp','=',$codresp)
                    ->where('actual.codofic','=',$codofic)
                    ->where('actual.unidad','=',$unidad)
                    ->get();
        return response()->json(['actuales'=>$actuales,'total'=>$actuales->count()]);
    }
    public function updateAsignacion(Request $request){
        $data = $request->data;
        $codresp = $request->codresp2;
        $codofic = $request->codofic2;
        try {
           $id_dev = New actaDevolucion();
           $id_dev->save();

           $id_asig = New actaAsignacion();
           $id_asig->save();

           for ($i=0; $i < count($data); $i++) {
                
                $id = $data[$i]['id'];

                $articuloant = Actual::where('id','=',$id)->first();

                $devolucion = New Asignaciones();
                $devolucion->codactual = $id;
                $devolucion->unidad = $articuloant->unidad;
                $devolucion->codresp = $articuloant->codresp;
                $devolucion->codofic = $articuloant->codofic;
                $devolucion->usuario = \Auth::user()->name;
                $devolucion->descripcion = 0;
                $devolucion->id_asignacion = $id_dev->id;
                $devolucion->save();

                $asignacion = New Asignaciones();
                $asignacion->codactual = $id;
                $asignacion->codresp = $codresp;
                $asignacion->codofic = $codofic;
                $asignacion->usuario = \Auth::user()->name;
                $asignacion->descripcion = 1;
                $asignacion->id_asignacion = $id_asig->id;
                $asignacion->save();

                $articulo = Actual::findOrFail($id);
                $articulo->codresp = $codresp;
                $articulo->codofic = $codofic;
                $articulo->estadodbf = 1;
                $articulo->save();
                
                $logs = new Logs();
                $logs->codactual = $data[$i]['id'];
                $logs->descripcion = 'Se Modifico el Responsable y Oficina';
                $logs->user = \Auth::user()->name;
                $logs->save();
                
                $codigo = $data[$i]['codigo'];

            }
            
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: '.$e->getMessage()]);
        }
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!','id_dev'=>$id_dev->id,'id_asig'=>$id_asig->id]);
    }
    public function repAsignaciones(Request $request){
        Date::setLocale('es');
        if(isset($request->fecha)){
            $fechaTitulo = Date::parse($request->fecha)->format('j \\de F \\de Y');
            }
        else{
            $fechaTitulo = Date::now()->format('j \\de F \\de Y');
        }
        $fechDerecha = Date::now()->format('d/M/Y');
        $unidad = $request->unidad;
        $descripunidad = Unidadadmin::where('unidad','=',$unidad)->first();
        $query=Actual::join('auxiliar',function ($join) {
                    $join->on('actual.codaux', '=', 'auxiliar.codaux');
                    $join->on('actual.codcont', '=', 'auxiliar.codcont');
                })
                ->join('estado','actual.codestado','=','estado.id')
                ->join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
                ->select('actual.*','auxiliar.nomaux','estado.nomestado')
                ->distinct()
                ->where('actual.unidad','=',$unidad)
                ->where('actual.codresp','=',$request->codresp)
                ->where('actual.codofic','=',$request->codofic)->get();
        if($request->data==''){
            $codcont = $request->codcont;
            $datos= $query;
            $total = $query->count();
        }else{
            $arraycodcont = explode(",", $request->data);
            $datos=[];

            for ($i=0; $i < count($arraycodcont); $i++) {
                for($j=0; $j < count($query); $j++){
                    
                    if($arraycodcont[$i] == $query[$j]->codcont){
                        $datos[]=$query[$j];
                        //array_push($datos, $actuales[$j]);
                    }
                }
            }
            $total = count($datos);
        }
        $responsable = Responsables::join('unidadadmin','resp.unidad','=','unidadadmin.unidad')
                                    ->join('oficina', function ($join) {
                                        $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                        $join->on('resp.codofic', '=', 'oficina.codofic');
                                    })
                                    ->select('resp.nomresp','oficina.nomofic','resp.cargo','oficina.codofic','resp.ci','unidadadmin.descrip as unidad')
                                    ->where('resp.unidad','=',$unidad)
                                    ->where('resp.codresp','=',$request->codresp)
                                    ->where('resp.codofic','=',$request->codofic)->first();
        
        $pdf=Pdf::loadView('plantillapdf.repAsignacion',['datos'=>$datos,'responsable'=>$responsable,'fechaTitulo'=>$fechaTitulo,'fechaDerecha'=>$fechDerecha,'total'=>$total, 'unidad'=>$unidad,'desuni'=>$descripunidad]);
        $pdf->set_paper(array(0,0,800,617));
        return $pdf->stream();
    }
    public function repDevoluciones(Request $request){
        
        Date::setLocale('es');
        if(isset($request->fecha)){
            $fechaTitulo = Date::parse($request->fecha)->format('j \\de F \\de Y');
            }
        else{
            $fechaTitulo = Date::now()->format('j \\de F \\de Y');
        }
        $fechDerecha = Date::now()->format('d/M/Y');
        $unidad = $request->unidad;
        $descripunidad = Unidadadmin::where('unidad','=',$unidad)->first();
        $query=Actual::join('auxiliar',function ($join) {
                    $join->on('actual.codaux', '=', 'auxiliar.codaux');
                    $join->on('actual.codcont', '=', 'auxiliar.codcont');
                })
                ->join('estado','actual.codestado','=','estado.id')
                ->join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
                ->select('actual.*','auxiliar.nomaux','estado.nomestado')
                ->distinct()
                ->where('actual.unidad','=',$unidad)
                ->where('actual.codresp','=',$request->codresp)
                ->where('actual.codofic','=',$request->codofic)->get();
        if($request->data==''){
            $codcont = $request->codcont;
            $datos = $query;
            $total = $datos->count();
        }else{
            $arraycodcont = explode(",", $request->data);
            $datos=[];
            for ($i=0; $i < count($arraycodcont); $i++) {
                for($j=0; $j < count($query); $j++){

                    if($arraycodcont[$i] == $query[$j]->codcont){
                        $datos[]=$query[$j];
                        //array_push($datos, $actuales[$j]);
                    }
                }
            }
            $total = count($datos);
        }
        $responsable =  Responsables::join('unidadadmin','resp.unidad','=','unidadadmin.unidad')
                                    ->join('oficina', function ($join) {
                                        $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                        $join->on('resp.codofic', '=', 'oficina.codofic');
                                    })
                                    ->select('resp.nomresp','oficina.nomofic','resp.cargo','oficina.codofic','resp.ci','unidadadmin.descrip as unidad')
                                    ->where('resp.codresp','=',$request->codresp)
                                    ->where('resp.codofic','=',$request->codofic)->first();
        $pdf=Pdf::loadView('plantillapdf.repDevolucion',['datos'=>$datos,'responsable'=>$responsable,'fechaTitulo'=>$fechaTitulo,'fechaDerecha'=>$fechDerecha,'total'=>$total, 'unidad'=>$unidad,'desuni'=>$descripunidad]);
        $pdf->set_paper(array(0,0,800,617));
        return $pdf->stream();
        
    }
    public function buscarActivoEstado(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $actuales = Actual::query()
            ->join('oficina', 'actual.codofic', '=', 'oficina.codofic')
            ->join('resp', function ($join) {
                $join->on('actual.codresp', '=', 'resp.codresp')
                    ->on('actual.codofic', '=', 'resp.codofic');
            })
            ->join('codcont', 'actual.codcont', '=', 'codcont.codcont')
            ->join('auxiliar', function ($join) {
                $join->on('actual.codaux', '=', 'auxiliar.codaux')
                    ->on('actual.codcont', '=', 'auxiliar.codcont');
            })
            ->select([
                'actual.*',
                'codcont.nombre',
                'auxiliar.nomaux',
                'oficina.nomofic',
                'resp.nomresp'
            ])
            ->where('actual.unidad','=',$request->unidad)
            ->where('auxiliar.unidad','=',$request->unidad)
            ->where('resp.unidad','=',$request->unidad)
            ->where('oficina.unidad','=',$request->unidad)
            ->when($buscar, fn($query) => $query->where("actual.$criterio", 'like', "%$buscar%"))
            ->distinct()
        ->paginate(5);
    
        return [
            'pagination' => [
                'total'        => $actuales->total(),
                'current_page' => $actuales->currentPage(),
                'per_page'     => $actuales->perPage(),
                'last_page'    => $actuales->lastPage(),
                'from'         => $actuales->firstItem(),
                'to'           => $actuales->lastItem(),
            ],
            'actuales' => $actuales
        ];
    }
    
    public function gcontable(Request $request){
        $codresp = $request->codresp;
        $codofic = $request->codofic;
        $unidad = $request->unidad;
        $gcontables = Actual::join('codcont','codcont.codcont','=','actual.codcont')
                        ->select('codcont.codcont','codcont.nombre')
                        ->distinct()
                        ->where('actual.codresp','=',$codresp)
                        ->where('actual.codofic','=',$codofic)
                        ->where('actual.unidad','=',$unidad)
                        ->get();
        return response()->json(['gcontables'=>$gcontables]);
    }
    public function auxiliar(Request $request){
        $codcont = $request->codcont;
        $codaux = $request->codaux;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $unidad = $request->unidad;
        $query=Actual::select('id','codigo','descripcion as descrip')
            ->where('codcont','=',$codcont)
            ->where('codaux','=',$codaux)
            ->where('unidad','=',$unidad);
        if($buscar==''){
            $actuales = $query->get();
        }else{
            if($criterio=='codigo'){
            $actuales = $query->where('actual.'.$criterio, 'like', '%'. $buscar . '%')->get();
            }
            else{
                $actuales = $query->where('actual.'.$criterio.'cion', 'like', '%'. $buscar . '%')->get();
            }
        }
        return response()->json(['actuales'=>$actuales,'totalactuales'=>$actuales->count()]);
    }
    public function responsable(Request $request){
        $codofic = $request->codofic;
        $codresp = $request->codresp;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $unidad = $request->unidad;
        $query = Actual::select('id','codigo','descripcion')
            ->where('codofic','=',$codofic)
            ->where('codresp','=',$codresp)
           ->where('unidad','=',$unidad);
        if($buscar==''){
            $actuales = $query->get();
        }else{
            $actuales = $query->where('actual.'.$criterio, 'like', '%'. $buscar . '%')->get();
            
        }
        return response()->json(['actuales'=>$actuales,'totalactuales'=>$actuales->count()]);
    }
    public function generarcodigo(Request $request){
        $unidad=Unidadadmin::where('unidad','=',$request->unidad)->first();
        $gcont=CodigoContable::where('id','=',$request->idcont)->first();
        $oficina=Oficinas::where('id','=',$request->idofic)->where('unidad','=',$unidad->unidad)->first();
        $correlativo = Actual::where('codcont','=',$gcont->codcont)->where('unidad','=',$unidad->unidad)->count();
        if($gcont->id<10){
            $codigo = '0'.$gcont->id.'-'.$unidad->unidad;
        }else{
           $codigo = $gcont->id.'-'.$unidad->unidad;}
        return response()->json(['codigo'=>$codigo,'correlativo'=>$correlativo]);
    }
}
