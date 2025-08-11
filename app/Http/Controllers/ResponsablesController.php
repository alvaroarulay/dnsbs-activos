<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Responsables;
use App\Models\Unidadadmin;
use App\Models\Actual;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;

class ResponsablesController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idrol = \Auth::user()->idrol;
        $unidadv = trim($request->unidad);
        $unidad = \Auth::user()->unidad;
        $a = Unidadadmin::select('descrip')->where('unidad','=',trim($unidad))->first();
        $b = Unidadadmin::select('ciudad')->where('unidad','=',trim($unidad))->first();
        $titulo = $a->descrip.' - '.$b->ciudad;
        if($idrol == 1){
            $total = Responsables::count('id');
            if($unidadv == ''){
                if ($buscar == ''){
                    $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('resp.codofic', '=', 'oficina.codofic');
                                })
                                ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                                'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                                ->where('resp.unidad','=',trim($unidad))
                                ->distinct()
                                ->paginate(10);
                    return [
                        'pagination' => [
                            'total'        => $responsables->total(),
                            'current_page' => $responsables->currentPage(),
                            'per_page'     => $responsables->perPage(),
                            'last_page'    => $responsables->lastPage(),
                            'from'         => $responsables->firstItem(),
                            'to'           => $responsables->lastItem(),
                        ],
                        'responsables' => $responsables,
                        'total' => $total,
                        'idrol' => $idrol,
                        'titulo' => $titulo
                    ];
    
                }
                else{
                    $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('resp.codofic', '=', 'oficina.codofic');
                                })
                                ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                                'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                                ->where('resp.unidad','=',trim($unidad))
                                ->distinct()
                                ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')
                                ->paginate(10);
                    return [
                        'pagination' => [
                            'total'        => $responsables->total(),
                            'current_page' => $responsables->currentPage(),
                            'per_page'     => $responsables->perPage(),
                            'last_page'    => $responsables->lastPage(),
                            'from'         => $responsables->firstItem(),
                            'to'           => $responsables->lastItem(),
                        ],
                        'responsables' => $responsables,
                        'total' => $total,
                        'idrol' => $idrol,
                        'titulo' => $titulo
                    ];
                
                }
            }else{
                if ($buscar==''){
                    $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('resp.codofic', '=', 'oficina.codofic');
                                })
                                ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                                'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                                ->where('resp.unidad','=',$unidadv)
                                ->distinct()
                                ->paginate(10);
                    return [
                        'pagination' => [
                            'total'        => $responsables->total(),
                            'current_page' => $responsables->currentPage(),
                            'per_page'     => $responsables->perPage(),
                            'last_page'    => $responsables->lastPage(),
                            'from'         => $responsables->firstItem(),
                            'to'           => $responsables->lastItem(),
                        ],
                        'responsables' => $responsables,
                        'total' => $total,
                        'idrol' => $idrol,
                        'titulo' => $titulo
                    ];
                
                }
                else{
                    $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('resp.codofic', '=', 'oficina.codofic');
                                })
                                ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                                'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                                ->where('resp.unidad','=',$unidadv)
                                ->distinct()
                                ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')
                                ->paginate(10);
                    return [
                        'pagination' => [
                            'total'        => $responsables->total(),
                            'current_page' => $responsables->currentPage(),
                            'per_page'     => $responsables->perPage(),
                            'last_page'    => $responsables->lastPage(),
                            'from'         => $responsables->firstItem(),
                            'to'           => $responsables->lastItem(),
                        ],
                        'responsables' => $responsables,
                        'total' => $total,
                        'idrol' => $idrol,
                        'titulo' => $titulo
                    ];
                }
            }
           
        }
        else{
            $total = Responsables::where('unidad','=',$unidad)->count();
             if ($buscar==''){
                $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                            ->join('oficina', function ($join) {
                                $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                $join->on('resp.codofic', '=', 'oficina.codofic');
                            })
                            ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                            'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                            ->where('resp.unidad','=',$unidad)
                            ->distinct()
                ->paginate(10);
                return [
                    'pagination' => [
                        'total'        => $responsables->total(),
                        'current_page' => $responsables->currentPage(),
                        'per_page'     => $responsables->perPage(),
                        'last_page'    => $responsables->lastPage(),
                        'from'         => $responsables->firstItem(),
                        'to'           => $responsables->lastItem(),
                    ],
                    'responsables' => $responsables,
                    'total' => $total,
                    'idrol' => $idrol,
                    'titulo' => $titulo
                ];

            }
            else{
                $responsables = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                            ->join('oficina', function ($join) {
                                $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                $join->on('resp.codofic', '=', 'oficina.codofic');
                            })
                            ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
                            'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
                            ->where('resp.unidad','=',$unidad)
                            ->distinct()
                ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')
                ->paginate(10);
                return [
                    'pagination' => [
                        'total'        => $responsables->total(),
                        'current_page' => $responsables->currentPage(),
                        'per_page'     => $responsables->perPage(),
                        'last_page'    => $responsables->lastPage(),
                        'from'         => $responsables->firstItem(),
                        'to'           => $responsables->lastItem(),
                    ],
                    'responsables' => $responsables,
                    'total' => $total,
                    'idrol' => $idrol,
                    'titulo' => $titulo
                ];

            }
        }
        
    }
    public function buscarRespActivo(Request $request){
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $unidad = $request->unidad;
        
        $total = Responsables::where('unidad','=',$unidad)->get();
        $idrol = \Auth::user()->idrol;
        if ($buscar==''){
            $responsables = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
            ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
            'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
            ->distinct()
            ->where('resp.unidad','=',$unidad)->paginate(10);
        }
        else{
            $responsables = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
            ->select('resp.id','resp.codofic','resp.codresp','resp.nomresp','resp.cargo','resp.estado',
            'resp.ci','oficina.nomofic','resp.api_estado','resp.cod_exp')
            ->distinct()
            ->where('resp.unidad','=',$unidad)
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')
            ->paginate(10);
        }
        

        return [
            'pagination' => [
                'total'        => $responsables->total(),
                'current_page' => $responsables->currentPage(),
                'per_page'     => $responsables->perPage(),
                'last_page'    => $responsables->lastPage(),
                'from'         => $responsables->firstItem(),
                'to'           => $responsables->lastItem(),
            ],
            'responsables' => $responsables,
            'total' => $total->count(),
            'idrol' => $idrol
        ];
    }
    public function store(Request $request)
    {
       // $unidad = $request->unidad;
        $fecha = Carbon::now()->format('Ymd');
        $unidad = Unidadadmin::where('id','=',$request->idunidad)->first();
        try { 
        
            $codofic = Responsables::where('codofic','=',$request->codofic)->where('unidad','=',$unidad->unidad)->count();
            $responsable = new Responsables();
            $responsable->entidad='0015';
            $responsable->unidad=$unidad->unidad;
            $responsable->codofic = $request->codofic;
            $responsable->codresp = $codofic + 1;
            $responsable->nomresp = $request->nomresp;
            $responsable->cargo = $request->cargo;
            $responsable->observ = $request->observ;
            $responsable->ci = $request->ci;
            $responsable->feult = $fecha;
            $responsable->usuar = \Auth::user()->username;
            $responsable->cod_exp = $request->expedido;
            $responsable->api_estado = $request->estado;
            $responsable->estado = $request->estado;
            $responsable->custodio = 0;
            $responsable->estadodbf = 1;
            $responsable->save();

            $co = Responsables::where('id','=',$responsable->id)->first();

            return response()->json(['message' => 'Datos Guardados Correctamente!!!','codofic'=>$co->codofic,'idunidad'=>$request->idunidad]);   
            } catch (Exception $e) {
                return response()->json(['message' => 'Excepción capturada: '.  $e->getMessage()]);
            }
    }
    public function update(Request $request)
    {
       try{
            $responsable = Responsables::findOrFail($request->id);
            $responsable->nomresp = $request->nomresp;
            $responsable->cargo = $request->cargo;
            $responsable->ci = $request->ci;
            $responsable->cod_exp = $request->cod_exp;
            $responsable->observ = $request->observ;
            $responsable->api_estado = $request->estado;
            $responsable->estado = $request->estado;
            $responsable->estadodbf = 1;
            $responsable->save();
        
            return response()->json(['message' => 'Datos Actualizados Correctamente!!!']);
       }catch(Exception $e){
            return response()->json(['message'=>'Excepción capturada: '. $e->getMessage()]);
       }
    }
    
    public function buscarResponsable(Request $request){

        //if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $responsable = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
        ->distinct()
        ->where('resp.unidad','=',$request->unidad)
        ->where('resp.ci','=', $filtro)
        ->select('resp.id','resp.nomresp','resp.cargo','oficina.nomofic','resp.estado','resp.codresp','resp.codofic')->first();
        return response()->json(['responsable' => $responsable]);
    }
    public function buscarRespxcodigo(Request $request){

        //if (!$request->ajax()) return redirect('/');

        $codofic = $request->codofic;
        $codresp = $request->codresp;
        $responsable = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
        ->where('resp.codresp','=', $codresp)
        ->where('resp.codofic','=', $codofic)
        ->select('resp.id','resp.nomresp','resp.cargo','oficina.nomofic','resp.api_estado','resp.codresp','resp.codofic')->first();
        return response()->json(['responsable' => $responsable]);
    }
    public function listarResponsable(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        else{
            $articulos = Articulo::join('categorias','articulos.idcategoria','=','categorias.id')
            ->select('articulos.id','articulos.idcategoria','articulos.codigo','articulos.nombre','categorias.nombre as nombre_categoria','articulos.precio_venta','articulos.stock','articulos.descripcion','articulos.condicion')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->where('articulos.stock','>','0')
            ->orderBy('articulos.id', 'desc')->paginate(10);
        }
        

        return ['articulos' => $articulos];
    }
    public function repResponsables(){
        $idrol = \Auth::user()->idrol;
        if($idrol == 1){
            $responsable = Responsables::join('unidadadmin','unidadadmin.unidad','=','resp.unidad')
                                ->join('oficina', function ($join) {
                                    $join->on('unidadadmin.unidad', '=', 'oficina.unidad');
                                    $join->on('resp.codofic', '=', 'oficina.codofic');
                                })
                                    ->select('resp.nomresp','resp.ci','oficina.nomofic','resp.cargo',
                                    'resp.observ')->distinct()->get();
        }
        else
        {
        $responsable = Responsables::join('oficina','resp.codofic','=','oficina.codofic')
                                    ->select('resp.nomresp','resp.ci','oficina.nomofic','resp.cargo',
                                    'resp.observ')
                                    ->where('oficina.unidad','=',\Auth::user()->unidad)
                                    ->where('resp.unidad','=',\Auth::user()->unidad)
                                    ->distinct()->get();
                                }
        return response()->json(['responsable' => $responsable]);                      
    }
    public function listarporOficina(Request $request){
        $codofic = $request->codofic;
        $unidad = Unidadadmin::where('id','=',$request->idunidad)->first();
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query=Responsables::join('cla_depts','resp.cod_exp','=','cla_depts.id')->select('resp.*','cla_depts.sigla')
        ->where('resp.codofic','=',$codofic)
        ->where('resp.unidad','=',$unidad->unidad);
        if($buscar==''){
            $responsables = $query->get();
        }else{
            $responsables = $query->where('resp.'.$criterio, 'like', '%'. $buscar . '%')->get();
        }
        return [
                'responsables' => $responsables,
                'total'=>$responsables->count()
                ];
    }
    public function responsables($id){
        return Responsables::where('codofic', $id)->get();
    }
    public function respactual($codofic,$unidad){
        
        return response()->json(Responsables::where('unidad','=',$unidad)->where('codofic','=',$codofic)->get());

    }
}
