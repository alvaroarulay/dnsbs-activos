<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actual;
use App\Models\Responsables;
use App\Models\Oficinas;
use App\Models\Auxiliares;
use XBase\Enum\FieldType;
use XBase\Enum\TableType;
use XBase\Header\Column;
use XBase\Header\HeaderFactory;
use XBase\TableCreator;
use XBase\TableEditor;
use XBase\TableReader;
use Carbon\Carbon;

class DbfsDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup de base de datos Vsiaf';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->editarActual();
            $this->editarAuxiliar();
            $this->editarResponsable();
            $this->editarOficina();

            return Command::SUCCESS;
        } catch (Exception $e) {

           return Comand::ERROR;
        }
    }
    protected function editarActual(){
        try {
            $datos = Actual::where('estadodbf','=', 1)->get();
            $datosMap = [];
            foreach ($datos as $dato) {
                $datosMap[$dato->codigo] = [
                    'codcont' => $dato->codcont,
                    'codaux' => $dato->codaux,
                    'codofic' => $dato->codofic,
                    'codresp' => $dato->codresp,
                    'descripcion' => $this->limpiarTexto($dato->descripcion),
                    'observ'=>$dato->observ,
                    'codestado' => $dato->codestado,
                    'codigosec' => $dato->codigosec,
                ];
            }
            $table = new TableEditor(public_path('vsiaf/dbfs/ACTUAL.DBF'), ['encoding' => 'cp1252']);
                while ($record = $table->nextRecord()) {
                $codigo = $record->get('codigo');
                if (isset($datosMap[$codigo])) {
                    $dato = $datosMap[$codigo];
                    $record->set('codcont', $dato['codcont']);
                    $record->set('codaux', $dato['codaux']);
                    $record->set('codofic',$dato['codofic']);
                    $record->set('codresp',$dato['codresp']);
                    $record->set('descrip', $dato['descripcion']);
                    $record->set('observ',$dato['observ']);
                    $record->set('codestado', $dato['codestado']);
                    $record->set('codigosec', $dato['codigosec']);
                    $table->writeRecord();
                }
            }
            $table->save()->close();
            Actual::where('estadodbf','=', 1)->update(['estadodbf' => 0]);

        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }

    }
    protected function editarAuxiliar(){
        try {
            $datos = Auxiliares::where('estadodbf','=', 1)->get();
            $datosMap = [];
            foreach ($datos as $dato) {
                $datosMap[$dato->codaux . '|' . $dato->codcont] = [
                    'nomaux' => $this->limpiarTexto($dato->nomaux),
                    'codaux' => $dato->codaux,
                    'codcont' => $dato->codcont,
                ];
            }

            $table = new TableEditor(public_path('vsiaf/dbfs/auxiliar.DBF'), ['encoding' => 'cp1252']);

            while ($record = $table->nextRecord()) {
                $codaux = $record->get('codaux');
                $codcont = $record->get('codcont');
                $key = $codaux . '|' . $codcont;
                if (isset($datosMap[$key])) {
                    $dato = $datosMap[$key];
                    $record->set('nomaux', $dato['nomaux']); 
                    $table->writeRecord();
                }
            }

            $table->save()->close();

            Auxiliares::where('estadodbf','=', 1)->update(['estadodbf' => 0]);

        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }

    }
    protected function editarResponsable(){
        try {
            $datos = Responsables::where('estadodbf', 1)->get();
            $datosMap = [];
            foreach ($datos as $dato) {
                $datosMap[$dato->codresp . '|' . $dato->codofic . '|' . $dato->unidad] = [
                    'entidad'=>$dato->entidad,
                    'unidad'=>$dato->unidad,
                    'codofic'=>$dato->codofic,
                    'codresp'=>$dato->codresp,
                    'nomresp'=>$this->limpiarTexto($dato->nomresp),
                    'cargo'=>$this->limpiarTexto($dato->cargo),
                    'feult'=> $dato->feult,
                    'observ'=>$dato->observ,
                    'ci'=>$dato->ci,
                    'usuar'=>$dato->usuar,
                    'cod_exp'=>$dato->cod_exp,
                    'api_estado'=>$dato->api_estado,
                ];
            }

            $table = new TableEditor(public_path('vsiaf/dbfs/RESP.DBF'), ['encoding' => 'cp1252']);
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
                    $record->set('feult', $dato['feult']);
                    $record->set('ci', $dato['ci']);
                    $record->set('cod_exp', $dato['cod_exp']);
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
                    $newRecord->set('feult', $dato['feult']);
                    $newRecord->set('ci', $dato['ci']);
                    $newRecord->set('usuar', $dato['usuar']);
                    $newRecord->set('cod_exp', $dato['cod_exp']);
                    $newRecord->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                }
            }

            $table->save()->close();
            Responsables::where('estadodbf', 1)->update(['estadodbf' => 0]);
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
        }

    }
    protected function editarOficina(){
        try {
            $datos = Oficinas::where('estadodbf', 1)->get();
            $datosMap = [];
            foreach ($datos as $dato) {
                $datosMap[$dato->codofic . '|' . $dato->unidad] = [
                    'entidad'=>$dato->entidad,
                    'unidad'=>$dato->unidad,
                    'codofic'=>$dato->codofic,
                    'nomofic'=>$this->limpiarTexto($dato->nomofic),
                    'feult'=> Carbon::parse($dato->feult)->format('d/m/y'),
                    'observ'=>$dato->observ,
                    'usuar'=>$dato->usuar,
                    'api_estado'=>$dato->api_estado,
                ];
            }

            $table = new TableEditor(public_path('vsiaf/dbfs/oficina.DBF'), ['encoding' => 'cp1252']);
            $procesados = [];
            while ($record = $table->nextRecord()) {
                $codofic = $record->get('codofic');
                $unidad = $record->get('unidad');
                $key =  $codofic . '|' .$unidad;
                if (isset($datosMap[$key])) {
                    $dato = $datosMap[$key];
                    $record->set('nomofic', $dato['nomofic']);
                    $record->set('observ',$dato['observ']);
                    $record->set('feult', $dato['feult']);
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
                    $newRecord->set('observ',$dato['observ']);
                    $newRecord->set('feult', $dato['feult']);
                    $newRecord->set('usuar', $dato['usuar']);
                    $newRecord->set('api_estado', $dato['api_estado']);
                    $table->writeRecord();
                }
            }

            $table->save()->close();
            Oficinas::where('estadodbf', 1)->update(['estadodbf' => 0]);
        } catch (Exception $e) {
        return response()->json(['message' => 'Excepción capturada: '+  $e->getMessage()]);
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
}
