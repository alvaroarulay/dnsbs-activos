<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use App\Models\Actual;
use App\Models\Auxiliares;
use App\Models\Oficinas;
use App\Models\Responsables;
use App\Models\Dbbackup;
use XBase\Enum\FieldType;
use XBase\Enum\TableType;
use XBase\Header\Column;
use XBase\Header\HeaderFactory;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;

class ZipArchiveController extends Controller
{
    public function downloadZip()
    {         
        try {
                $this->editarActual(); 
                $this->editarAuxiliar(); 
                $this->editarResponsable(); 
                $this->editarOficina();   
                $zip = new ZipArchive;
                $date = Carbon::now();
                $fileName = 'VS'.$date->format('Ymd') . '.' .'zip';
                if ($zip->open(storage_path($fileName), ZipArchive::CREATE) === TRUE)
                {
                    $files = \File::files(storage_path('vsiaf/dbfs'));

                    foreach ($files as $key => $value) {
                        $file = basename($value);
                        $zip->addFile($value, $file);
                    }

                    $zip->close();
                }
                return response()->download(storage_path($fileName));
            
            } catch (Exception $e) {
               return print_r("hubo un error");
            }
            
    }
    public function actualizardb()
    {
        try {
             
            //$this->actualizarOficinas();
            $this->actualizarResponsable(); 
            $this->actualizarActual();
            //$this->editarActual(); 
            //$this->editarAuxiliar(); 
            //$this->editarResponsable();
            //$this->editarOficina();
            return response()->json(['mensaje' => 'Datos actualizados correctamente.']);
       
        } catch (Exception $e) {
            return response()->json(['message' => 'Excepción capturada: ' .  $e->getMessage()]);
        }
    }
    protected function editarActual(){
        try {
            $datos = Actual::where('estadodbf','=', 1)->get();
            $datosMap = [];
            if($datos->isEmpty()){
                return false;
            }else{
            foreach ($datos as $dato) {
                $datosMap[$dato->codigo] = [
                    'unidad'=>$dato->unidad,
                    'entidad'=>$dato->entidad,
                    'codigo'=>$dato->codigo,
                    'codcont' => $dato->codcont,
                    'codaux' => $dato->codaux,
                    'vidautil'=>$dato->vidautil,
                    'descripcion' => $this->limpiarTexto($dato->descripcion),
                    'costo'=>$dato->costo,
                    'depacu'=>$dato->depacu,
                    'mes'=>$dato->mes,
                    'ano'=>$dato->año,
                    'b_rev'=>$dato->b_rev,
                    'dia'=>$dato->dia,
                    'codofic' => $dato->codofic,
                    'codresp' => $dato->codresp,
                    'observ'=>$dato->observ,
                    'dia_ant'=>$dato->dia_ant,
                    'mes_ant'=>$dato->mes_ant,
                    'ano_ant'=>$dato->ano_ant,
                    'vut_ant'=>$dato->vut_ant,
                    'costo_ant'=>$dato->costo_ant,
                    'band_ufv'=>$dato->band_ufv,
                    'codestado'=>$dato->codestado,
                    'cod_rube'=>$dato->cod_rube,
                    'nro_conv'=>$dato->nro_conv,
                    'org_fin'=>$dato->org_fin,
                    'feult'=>Carbon::parse($dato->created_at)->format('Ymd'),
                    'usuar'=>$dato->usuar,
                    'api_estado'=>$dato->api_estado,
                    'codigosec'=>$dato->codigosec,
                    'banderas'=>$dato->banderas,
                    'fec_mod'=>Carbon::parse($dato->update_at)->format('Ymd'),
                    'usu_mod'=>$dato->usu_mod,
                ];
            }
            $procesados = [];
            $table = new TableEditor(storage_path('vsiaf/dbfs/ACTUAL.DBF'), ['encoding' => 'cp1252']);
                while ($record = $table->nextRecord()) {
                $codigo = $record->get('codigo');
                if (isset($datosMap[$codigo])) {
                    $dato = $datosMap[$codigo];
                    $record->set('codaux', $dato['codaux']);
                    $record->set('descrip', $dato['descripcion']);
                    $record->set('observ', $dato['observ'] );
                    $record->set('codestado', $dato['codestado']);
                    $record->set('codigosec', $dato['codigosec']);
                    $record->set('fec_mod', $dato['fec_mod'] );
                    $record->set('usu_mod',$dato['usu_mod']);
                    $table->writeRecord();
                    $procesados[$codigo] = true;
                }
            }
             foreach ($datosMap as $codigo => $dato) {
                if (!isset($procesados[$codigo])) {
                    $record = $table->appendRecord();
                    $record->set('unidad',  $dato['unidad'] );
                    $record->set('entidad', $dato['entidad'] );
                    $record->set('codigo', $dato['codigo'] );
                    $record->set('codcont', $dato['codcont'] );
                    $record->set('codaux', $dato['codaux'] );
                    $record->set('vidautil', $dato['vidautil'] );
                    $record->set('descrip', $dato['descripcion'] );
                    $record->set('costo', $dato['costo'] );
                    $record->set('depacu', $dato['depacu'] );
                    $record->set('mes', $dato['mes'] );
                    $record->set('ano', $dato['ano'] );
                    $record->set('b_rev', (boolean)$dato['b_rev'] );
                    $record->set('dia', $dato['dia'] );
                    $record->set('codofic', $dato['codofic'] );
                    $record->set('codresp', $dato['codresp'] );
                    $record->set('observ', $dato['observ'] );
                    $record->set('dia_ant', $dato['dia_ant'] );
                    $record->set('mes_ant', $dato['mes_ant'] );
                    $record->set('ano_ant', $dato['ano_ant'] );
                    $record->set('vut_ant', $dato['vut_ant'] );
                    $record->set('costo_ant', $dato['costo_ant'] );
                    $record->set('band_ufv', (boolean)$dato['band_ufv'] );
                    $record->set('codestado', $dato['codestado'] );
                    $record->set('cod_rube', $dato['cod_rube'] );
                    $record->set('nro_conv', $dato['nro_conv'] );
                    $record->set('org_fin', $dato['org_fin'] );
                    $record->set('feult', $dato['feult'] );
                    $record->set('usuar', $dato['usuar'] );
                    $record->set('api_estado', $dato['api_estado'] );
                    $record->set('codigosec', $dato['codigosec'] );
                    $record->set('banderas', $dato['banderas'] );
                    $record->set('fec_mod', $dato['fec_mod'] );
                    $record->set('usu_mod', $dato['usu_mod'] );
                    $table->writeRecord();
                }
            }
            $table->save()->close();
            Actual::where('estadodbf','=', 1)->update(['estadodbf' => 0]);
            return true;
            }
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: ' .  $e->getMessage()]);
        }

    }
    protected function editarAuxiliar(){
        try {
            $datos = Auxiliares::where('estadodbf','=', 1)->get();
            $datosMap = [];
            if($datos->isEmpty()){
                return false;
            }else{
            foreach ($datos as $dato) {
                $datosMap[$dato->unidad . '|' . $dato->codaux . '|' . $dato->codcont] = [
                    'entidad' => $dato->entidad,
                    'unidad' => $dato->unidad,
                    'codcont' => $dato->codcont,
                    'codaux' => $dato->codaux,
                    'nomaux' => $this->limpiarTexto($dato->nomaux),
                    'observ' => $this->limpiarTexto($dato->observ),
                    'feult' => Carbon::parse($dato->created_at)->format('Ymd')
                ];
            }

            $table = new TableEditor(storage_path('vsiaf/dbfs/auxiliar.DBF'), ['encoding' => 'cp1252']);
            $procesados = [];
            while ($record = $table->nextRecord()) {
                $unidad = $record->get('unidad');
                $codaux = $record->get('codaux');
                $codcont = $record->get('codcont');
                $key = $unidad . '|' . $codaux . '|' . $codcont;
                if (isset($datosMap[$key])) {
                    $dato = $datosMap[$key];
                    $record->set('nomaux', $dato['nomaux']);
                    $record->set('observ', $dato['observ']);
                    $record->set('feult', $dato['feult']); 
                    $table->writeRecord();
                    $procesados[$key] = true;
                }
            }
            foreach ($datosMap as $key => $dato) {
                if (!isset($procesados[$key])) {
                    $newRecord = $table->appendRecord();
                    $newRecord->set('entidad', $dato['entidad']);
                    $newRecord->set('unidad', $dato['unidad']);
                    $newRecord->set('codcont', $dato['codcont']);
                    $newRecord->set('codaux', $dato['codaux']);
                    $newRecord->set('nomaux', $dato['nomaux']);
                    $newRecord->set('observ', $dato['observ']);
                    $newRecord->set('feult', $dato['feult']);
                    $newRecord->set('usuar', auth()->user()->username);
                    $table->writeRecord();
                }
            }
            $table->save()->close();

            Auxiliares::where('estadodbf','=', 1)->update(['estadodbf' => 0]);
            return true;
            }

        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }

    }
    protected function editarResponsable(){
        try {
            $datos = Responsables::where('estadodbf', 1)->get();
            $datosMap = [];
            if($datos->isEmpty()){
                return false;
            }else{
            foreach ($datos as $dato) {
                $datosMap[$dato->codresp . '|' . $dato->codofic . '|' . $dato->unidad] = [
                    'entidad'=>$dato->entidad,
                    'unidad'=>$dato->unidad,
                    'codofic'=>$dato->codofic,
                    'codresp'=>$dato->codresp,
                    'nomresp'=>$this->limpiarTexto($dato->nomresp),
                    'cargo'=>$this->limpiarTexto($dato->cargo),
                    'observ'=>$this->limpiarTexto($dato->observ),
                    'feult'=>$dato->feult,
                    'ci'=>$dato->ci,
                    'usuar'=>$dato->usuar,
                    'cod_exp'=>$dato->cod_exp,
                    'api_estado'=>$dato->api_estado,
                ];
            }

            $table = new TableEditor(storage_path('vsiaf/dbfs/RESP.DBF'), ['encoding' => 'cp1252']);
            $procesados = [];
            while ($record = $table->nextRecord()) {
                $codresp = $record->get('codresp');
                $codofic = $record->get('codofic');
                $unidad = $record->get('unidad');
                $key = $codresp . '|' . $codofic . '|' .$unidad;
                if (isset($datosMap[$key])) {
                    $dato = $datosMap[$key];
                    $record->set('nomresp', $dato['nomresp']);
                    $record->set('cargo', $dato['cargo']);
                    $record->set('observ',$dato['observ']);
                    $record->set('ci', $dato['ci']);
                    $record->set('feult',$dato['feult']);
                    $record->set('cod_exp', $dato['cod_exp']);
                    $record->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                    $procesados[$key] = true;
                }
            }
            foreach ($datosMap as $key => $dato) {
                if (!isset($procesados[$key])) {
                    $newRecord = $table->appendRecord();
                    $newRecord->set('entidad', $dato['entidad']);
                    $newRecord->set('unidad', $dato['unidad']);
                    $newRecord->set('codofic', $dato['codofic']);
                    $newRecord->set('codresp', $dato['codresp']);
                    $newRecord->set('nomresp', $dato['nomresp']);
                    $newRecord->set('cargo', $dato['cargo']);
                    $newRecord->set('observ',$dato['observ']);
                    $newRecord->set('ci', $dato['ci']);
                    $newRecord->set('feult',$dato['feult']);
                    $newRecord->set('usuar', $dato['usuar']);
                    $newRecord->set('cod_exp', $dato['cod_exp']);
                    $newRecord->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                }
            }

            $table->save()->close();
            Responsables::where('estadodbf', 1)->update(['estadodbf' => 0]);
            return true;
            }
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }

    }
    protected function editarOficina(){
        try {
            $datos = Oficinas::where('estadodbf', 1)->get();
            $datosMap = [];
            if($datos->isEmpty()){
                return false;
            }else{
            foreach ($datos as $dato) {
                $datosMap[$dato->codofic . '|' . $dato->unidad] = [
                    'entidad'=>$dato->entidad,
                    'unidad'=>$dato->unidad,
                    'codofic'=>$dato->codofic,
                    'nomofic'=>$this->limpiarTexto($dato->nomofic),
                    'usuar'=>$dato->usuar,
                    'api_estado'=>$dato->api_estado,
                ];
            }

            $table = new TableEditor(storage_path('vsiaf/dbfs/oficina.DBF'), ['encoding' => 'cp1252']);
            $procesados = [];
            while ($record = $table->nextRecord()) {
                $codofic = $record->get('codofic');
                $unidad = $record->get('unidad');
                $key =  $codofic . '|' .$unidad;
                if (isset($datosMap[$key])) {
                    $dato = $datosMap[$key];
                    $record->set('nomofic', $dato['nomofic']);
                    $record->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                    $procesados[$key] = true;
                }
            }
            foreach ($datosMap as $key => $dato) {
                if (!isset($procesados[$key])) {
                    $newRecord = $table->appendRecord();
                    $newRecord->set('entidad', $dato['entidad']);
                    $newRecord->set('unidad', $dato['unidad']);
                    $newRecord->set('codofic', $dato['codofic']);
                    $newRecord->set('nomofic', $dato['nomofic']);
                    $newRecord->set('usuar', $dato['usuar']);
                    $newRecord->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                }
            }

            $table->save()->close();
            Oficinas::where('estadodbf', 1)->update(['estadodbf' => 0]);
            return true;
            }
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: ' .  $e->getMessage()]);
        }

    }
    private function limpiarTexto($texto)
    {
        $replace = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N'
        ];
        return strtr($texto, $replace);
    }
    public function uploadZip(Request $request)
    {
        $request->validate([
            'archivo_zip' => 'required|file|mimes:zip'
        ]);

        $zipFile = $request->file('archivo_zip');
        $zipPath = $zipFile->getRealPath();

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $extractPath = storage_path('vsiaf/dbfs');
            Storage::deleteDirectory('vsiaf/dbfs');
            Storage::makeDirectory('vsiaf/dbfs');
            $zip->extractTo($extractPath);
            $zip->close();

            return response()->json(['mensaje' => 'Archivos reemplazados desde el ZIP.']);
        }

        return response()->json(['error' => 'No se pudo abrir el archivo ZIP.'], 500);
    }
    public function procesarZip(Request $request)
    {
        $this->importarAuxiliar();
        $this->importarUnidad();
        $this->importarOficina();
        $this->importarResp();
        $this->importarActual();
        return response()->json(['mensaje' => 'Datos actualizados correctamente.']);
    }
    public function importarAuxiliar()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE auxiliar');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $table = new TableReader(storage_path('vsiaf/dbfs/AUXILIAR.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('auxiliar')->insert([
                'entidad' => $record->get('entidad'),
                'unidad' => $record->get('unidad'), 
                'codcont' => $record->get('codcont'),
                'codaux' => $record->get('codaux'),
                'nomaux' => $record->get('nomaux'),
                'usuar' => $record->get('usuar'),
                'created_at'=>now(),
                'updated_at'=>now(),
          ]);
        }
    }
    public function importarUnidad()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE unidadadmin');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $table = new TableReader(storage_path('vsiaf/dbfs/UNIDADADMIN.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('unidadadmin')->insert([
            'entidad' =>$record->get('entidad'),
            'unidad' =>$record->get('unidad'),
            'descrip' => $record->get('descrip'),
            'ciudad' => $record->get('ciudad'),
            'estadouni' => $record->get('estadouni'),
            'created_at'=>now(),
            'updated_at'=>now(),
          ]);
        }
    }
    public function importarOficina()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE oficina');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $table = new TableReader(storage_path('vsiaf/dbfs/OFICINA.dbf'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('oficina')->insert([
            'entidad' => $record->get('entidad'),
            'unidad' => $record->get('unidad'),
            'codofic' => $record->get('codofic'),
            'nomofic' => $record->get('nomofic'),
            'feult' => $record->get('feult'),
            'usuar' => $record->get('usuar'),
            'api_estado' => $record->get('api_estado'),
            'created_at'=> now(),
            'updated_at'=> now(),
          ]);
        }
    }
    public function importarResp()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE resp');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $table = new TableReader(storage_path('vsiaf/dbfs/RESP.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            DB::table('resp')->insert([
            'entidad' =>$record->get('entidad'),
            'unidad' =>$record->get('unidad'),
            'codofic' =>$record->get('codofic'),
            'codresp' =>$record->get('codresp'),
            'nomresp' =>$record->get('nomresp'),
            'cargo' =>$record->get('cargo'),
            'ci' =>$record->get('ci'),
            'feult' =>$record->get('feult'),
            'usuar' =>$record->get('usuar'),
            'cod_exp' =>$record->get('cod_exp'),
            'api_estado' =>$record->get('api_estado'),
            'created_at'=>now(),
            'updated_at'=>now(),
          ]);
        }
    }
     public function importarActual()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('TRUNCATE TABLE actual');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $table = new TableReader(storage_path('vsiaf/dbfs/ACTUAL.DBF'),['encoding' => 'cp1252']);
        while ($record = $table->nextRecord()) {
            if($record->get('org_fin') == ''){
                $organismo = 0;
            }else{
                $organismo = $record->get('org_fin');
            }
            DB::table('actual')->insert([
            'unidad' => $record->get('unidad'), 
            'entidad' => $record->get('entidad'),
            'codigo' => $record->get('codigo'),
            'codcont' => $record->get('codcont'),
            'codaux' => $record->get('codaux'),
            'vidautil' => $record->get('vidautil'),
            'descripcion' => $record->get('descrip'),
            'costo' => $record->get('costo'),
            'depacu' => $record->get('depacu'),
            'mes' => $record->get('mes'), 
            'año' => $record->get('ano'), 
            'b_rev' => $record->get('b_rev'),
            'dia' => $record->get('dia'), 
            'codofic' => $record->get('codofic'),
            'codresp' => $record->get('codresp'),
            'dia_ant' => $record->get('dia_ant'), 
            'mes_ant' => $record->get('mes_ant'), 
            'año_ant' => $record->get('ano_ant'),
            'vut_ant' => $record->get('vut_ant'),
            'costo_ant' => $record->get('costo_ant'),
            'band_ufv' => $record->get('band_ufv'), 
            'codestado' => $record->get('codestado'),
            'cod_rube' => $record->get('cod_rube'),
            'nro_conv' => $record->get('nro_conv'),
            'org_fin' => $organismo,
            'usuar' => $record->get('usuar'),
            'api_estado' => $record->get('api_estado'),
            'codigosec' => $record->get('codigosec'),
            'banderas' => $record->get('banderas'),
            'fec_mod' => $record->get('fec_mod'),
            'usu_mod' => $record->get('usu_mod'),
            'created_at'=>now(),
            'updated_at'=>now(),
          ]);
        }
    }
    
    
    public function actualizarActual(){
        $table = new TableReader(storage_path('vsiaf/dbfs/ACTUAL.DBF'),['encoding' => 'cp1252']);
        $actuales=Actual::count();
        $contador = 0;

        while ($record = $table->nextRecord()) {
        $contador ++;
        if($actuales < $contador){
            DB::table('actual')->insert([
                'unidad' => $record->get('unidad'),
                'entidad' => $record->get('entidad'),
                'codigo' => $record->get('codigo'),
                'codcont' => $record->get('codcont'),
                'codaux' => $record->get('codaux'),
                'vidautil' => $record->get('vidautil'),
                'descripcion' => $record->get('descrip'),
                'costo' => $record->get('costo'),
                'depacu' => $record->get('depacu'),
                'mes' => $record->get('mes'), 
                'año' => $record->get('ano'), 
                'b_rev' => $record->get('b_rev'),
                'dia' => $record->get('dia'), 
                'codofic' => $record->get('codofic'),
                'codresp' => $record->get('codresp'),
                'observ' => $record->get('observ'),
                'dia_ant' => $record->get('dia_ant'), 
                'mes_ant' => $record->get('mes_ant'), 
                'año_ant' => $record->get('ano_ant'),
                'vut_ant' => $record->get('vut_ant'),
                'costo_ant' => $record->get('costo_ant'),
                'band_ufv' => $record->get('band_ufv'), 
                'codestado' => $record->get('codestado'),
                'cod_rube' => $record->get('cod_rube'),
                'nro_conv' => $record->get('nro_conv'),
                'org_fin' => $record->get('org_fin'),
                'usuar' => $record->get('usuar'),
                'api_estado' => $record->get('api_estado'),
                'codigosec' => $record->get('codigosec'),
                'banderas' => $record->get('banderas'),
                'fec_mod' => $record->get('fec_mod'),
                'usu_mod' => $record->get('usu_mod'),
            ]);
            }
        }
        $table->close();

        if($actuales == $contador){
            return false;
            } 
        else{
            return true;
            }
    }
    public function actualizarOficinas(){
        $table = new TableReader(storage_path('vsiaf/dbfs/OFICINA.DBF'),['encoding' => 'cp1252']);
        $oficinas=Oficinas::count();
        $contador = 0;

        while ($record = $table->nextRecord()) {
        $contador ++;
        if($oficinas < $contador){
            DB::table('oficina')->insert([
                'entidad' => $record->get('entidad'),
                'unidad' => $record->get('unidad'),
                'codofic' => $record->get('codofic'),
                'nomofic' => $record->get('nomofic'),
                'feult' => $record->get('feult'),
                'usuar' => $record->get('usuar'),
                'api_estado' => $record->get('api_estado'),
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
            }
        }
        $table->close();

        if($oficinas == $contador){
            return false;
            } 
        else{
            return true;
            }
    }
    public function actualizarResponsable(){
        $table = new TableReader(storage_path('vsiaf/dbfs/RESP.DBF'),['encoding' => 'cp1252']);
        $responsables=Responsables::count();
        $contador = 0;
      
        while ($record = $table->nextRecord()) {
            $contador ++;
            if($responsables < $contador){
                DB::table('resp')->insert([
                'entidad' =>$record->get('entidad'),
                'unidad' =>$record->get('unidad'),
                'codofic' =>$record->get('codofic'),
                'codresp' =>$record->get('codresp'),
                'nomresp' =>$record->get('nomresp'),
                'cargo' =>$record->get('cargo'),
                'observ' =>$record->get('observ'),
                'ci' =>$record->get('ci'),
                'feult' =>$record->get('feult'),
                'usuar' =>$record->get('usuar'),
                'cod_exp' =>$record->get('cod_exp'),
                'api_estado' =>$record->get('api_estado'),
                ]);
            }            
        }
        $table->close();
        if($responsables == $contador){
            return false;
        } 
        else{
            return true;
        }
      }
    public function subirSql(Request $request)
   {
        $request->validate([
            'archivo_sql' => 'required|file'
        ]);
        $nombre = Carbon::now()->format('Ymd'). '.sql';
        $ruta = storage_path('backups');
       
        //$ruta = storage_path('app/backups/');
        //Storage::put($ruta, $request->file('archivo_sql'));
        //Storage::put($ruta, file_get_contents($request->file('archivo_sql')));
        Storage::putFileAs('backups', $request->file('archivo_sql'), $nombre);
        /*

        // Eliminar si ya existe
        if (Storage::exists($ruta)) {
            Storage::delete($ruta);
        }

        // Guardar nuevo archivo con el mismo nombre
        */

        return response()->json(['mensaje' => 'Archivo reemplazado correctamente']);
    }
    public function procesarSql($id){
            $base = Dbbackup::where('id', $id)->first();

            if (!$base || !$base->archivo) {
                return response()->json(['error' => 'Archivo no encontrado en base de datos.'], 404);
            }

            $ruta = storage_path("app/backups/" . $base->archivo);

            if (!file_exists($ruta)) {
                return response()->json(['error' => 'Archivo no existe en el sistema.'], 404);
            }

            $tabla = 'actual'; // Puedes hacerlo dinámico si lo deseas

            $contenido = file_get_contents($ruta);

            $bloques = preg_split('/(?=^CREATE TABLE|^INSERT INTO)/m', $contenido);

            $bloquesFiltrados = collect($bloques)->filter(function ($bloque) use ($tabla) {
                return str_starts_with(trim($bloque), "INSERT INTO") && str_contains($bloque, "`$tabla`");
            })->implode("\n");

            if (empty($bloquesFiltrados)) {
                return response()->json(['error' => "No se encontraron datos para la tabla `$tabla`."]);
            }

            try {
                // Desactivar llaves foráneas
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Vaciar la tabla
                DB::statement("TRUNCATE TABLE `$tabla`");

                // Insertar los datos filtrados
                DB::unprepared($bloquesFiltrados);

                // Activar llaves foráneas
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                return response()->json(['success' => "Tabla `$tabla` restaurada exitosamente."]);
            } catch (\Exception $e) {
                // Activar llaves foráneas por si ocurre error antes
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                return response()->json(['error' => 'Error al ejecutar SQL: ' . $e->getMessage()], 500);
            }
    
    }
}
