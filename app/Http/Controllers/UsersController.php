<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Actual;
use App\Models\Responsables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $unidad = Responsables::select('unidad')->where('codresp','=',\Auth::user()->codresp)->where('codofic','=',\Auth::user()->codofic)->first();
        $buscar = $request->buscar;
        $idrol = \Auth::user()->idrol;
        if($request->unidad==''){
            if ($buscar==''){
                $personas = User::join('roles','users.idrol','=','roles.id')
                ->select('users.id','users.name','users.username','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->where('users.unidad','=',$unidad->unidad)
                ->orderBy('users.id', 'desc')
                ->paginate(10);
            }
            else{
                $personas = User::join('roles','users.idrol','=','roles.id')
                ->select('users.id','users.name','users.username','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->where('users.unidad','=',$unidad->unidad)
                ->where('users.name', 'like', '%'. $buscar . '%')->orderBy('users.id', 'desc')->paginate(10);
            }
        }else{
            if ($buscar==''){
                $personas = User::join('roles','users.idrol','=','roles.id')
                ->select('users.id','users.name','users.username','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->where('users.unidad','=',$request->unidad)
                ->orderBy('users.id', 'desc')
                ->paginate(10);
            }
            else{
                $personas = User::join('roles','users.idrol','=','roles.id')
                ->select('users.id','users.name','users.username','users.email','users.password','users.condicion','users.idrol','roles.nombre as rol')
                ->where('users.unidad','=',$request->unidad)
                ->where('users.name', 'like', '%'. $buscar . '%')->orderBy('users.id', 'desc')->paginate(10);
            }
        }
        
        
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas,
            'idrol'=>$idrol
        ];
    }
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        
        $user = new User();
        $user->name = $request->name;
        $user->unidad = $request->unidad;
        $user->email=$request->email;
        $user->username = $request->username;
        $user->password = Hash::make( $request->password);
        $user->condicion = 1;
        $user->codresp = $request->codresp;
        $user->codofic = $request->codofic; 
        $user->idrol = $request->idrol;
        $user->save();
        return response()->json(['message' => 'Usuario Creado Correctamente!!!']);
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try{
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email=$request->email;
            $user->username = $request->username;
            $user->password = Hash::make( $request->password);
            $user->condicion = 1; 
            $user->idrol = $request->idrol;
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Usuario Actualizado Correctamente!!!']);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'hubo un error!!!']);
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
    public function administrador(Request $request)
    {
        try{
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->idrol = $request->idrol;
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Usuario Actualizado Correctamente!!!']);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'hubo un error!!!']);
        }
    }

    public function operador(Request $request)
    {
        try{
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->idrol = $request->idrol;
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Usuario Actualizado Correctamente!!!']);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'hubo un error!!!']);
        }
    }
    public function activosxuser(Request $request){
        $codresp = \Auth::user()->codresp;
        $codofic = \Auth::user()->codofic;
        $actuales = Actual::join('unidadadmin','actual.unidad','=','unidadadmin.unidad')
                    ->join('oficina', function ($join) {
                        $join->on('actual.codofic', '=', 'oficina.codofic');
                    })
                    ->join('resp', function ($join) {
                                $join->on('resp.codofic', '=', 'oficina.codofic');
                                $join->on('actual.codresp', '=', 'resp.codresp');
                                $join->on('actual.codofic', '=', 'resp.codofic');
                            })
                    ->select('actual.id', 'unidadadmin.descrip as unidad', 'actual.codigo', 'oficina.nomofic',
                            'actual.descripcion', 'actual.codestado as estado', 'actual.codigosec', 'actual.observ',)
                    ->where('actual.codresp','=',$codresp)
                    ->where('actual.codofic','=',$codofic)
                    ->distinct()
                    ->paginate(6);
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
                        ];
    }
    public function responsable(Request $request)
    {   
        $codresp = \Auth::user()->codresp;
        $codofic = \Auth::user()->codofic;
        $unidad =\Auth::user()->unidad;
        $responsable = User::join('resp',function($join){
            $join->on('resp.unidad', '=', 'users.unidad');
            $join->on('resp.codofic', '=', 'users.codofic');
            $join->on('resp.codresp', '=', 'users.codresp');
        })
        ->select('users.id as user','resp.id as resp','resp.nomresp','resp.cargo','resp.ci','resp.cod_exp','users.username','users.email')
        ->where('resp.unidad','=',$unidad)
        ->where('resp.codresp','=',$codresp)
        ->where('resp.codofic','=',$codofic)
        ->first();
        return [
            'responsable' => $responsable,
        ];
    }
    public function updateresponsable(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
       

        try{
            DB::beginTransaction();

            $responsable = Responsables::findOrFail($request->idresp);
            $responsable->nomresp = $request->nomresp;
            $responsable->cargo = $request->cargo;
            $responsable->ci = $request->ci;
            $responsable->cod_exp = $request->cod_exp;
            $responsable->estadodbf = 1;
            $responsable->save();

            $user = User::findOrFail($request->iduser);
            $user->email=$request->email;
            $user->username = $request->username;
            $user->password = Hash::make( $request->password);
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Usuario Actualizado Correctamente!!!']);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'hubo un error!!!']);
        }
    }
}
