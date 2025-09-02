<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Dbbackup;
use Carbon\Carbon;
class DBbackupController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $query= Dbbackup::select('id','archivo','usuar', 'created_at')
            ->orderBy('id', 'desc');
        if ($buscar==''){
            $base = $query->paginate(10);
        }
        else{
            $base = $query->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(10);
        }
        return [
                'pagination' => [
                'total'        => $base->total(),
                'current_page' => $base->currentPage(),
                'per_page'     => $base->perPage(),
                'last_page'    => $base->lastPage(),
                'from'         => $base->firstItem(),
                'to'           => $base->lastItem(),
            ],
            'base' => $base,
        ];
    }
    public function store(Request $request)
    {
      try {
            $request->validate([
                'archivo_sql' => 'required|file|mimes:sql,txt'
            ]);

            $archivo = $request->file('archivo_sql');
            if (!$archivo) {
                throw new \Exception('Archivo no recibido.');
            }

            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();

            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            Storage::putFileAs('backups', $archivo, $nombreArchivo);

            $dbbackup = new Dbbackup();
            $dbbackup->archivo = $nombreArchivo;
            $dbbackup->usuar = auth()->user()->username;
            $dbbackup->save();

            return response()->json(['success' => 'Backup realizado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al realizar el backup: ' . $e->getMessage()], 500);
        }
    }
    public function destroy($id){
          try {
                $db = Dbbackup::findOrFail($id);
                $archivoPath = 'backups/' . $db->archivo;
                if (Storage::exists($archivoPath)) {
                    Storage::delete($archivoPath);
                }
                $db->delete();
            return response()->json(['message' => 'Datos eliminados correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
    public function downloadSql(){

        $backupPath = storage_path('app/backups');
        $backupPath = str_replace('/', '\\', $backupPath); 

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        try {
            $date = Carbon::now();
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $fileName = $date->format('Ymd_His') . '.sql';
            //$fullPath = $backupPath . '\\' . $fileName;


            // Ruta completa al ejecutable pg_dump en Windows
        // $pgDumpPath = '"C:\\Program Files\\PostgreSQL\\17\\bin\\pg_dump.exe"'; // ajusta según tu instalación

            // Comando con comillas para rutas y variables
            //$command = "PGPASSWORD={$password} && "."C:\\Program Files\\PostgreSQL\\17\\bin\\pg_dump.exe"." -U {$username} -h {$host} -d {$database} -F p -f {$fullPath}";
            //$command = "SET PGPASSWORD={$password} && {$pgDumpPath} -U {$username} -h {$host} -d {$database} -F p -f \"{$backupPath}\\{$fileName}\"";
            $command = "PGPASSWORD='{$password}' pg_dump --username={$username} --host={$host} --dbname={$database} --format=plain --file={$backupPath}/{$fileName}";

            exec($command, $output, $resultCode);

            if ($resultCode !== 0) {
                return response()->json(['error' => 'Error al ejecutar pg_dump.'.$command], 500);
            }

            $backup = new Dbbackup();
            $backup->archivo = $fileName;
            $backup->usuar = \Auth::user()->username;
            $backup->save();

            return response()->download($backupPath . '/' . $fileName);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hubo un error: ' . $e->getMessage()], 500);
        }
    }
     public function procesarSql($id){
        $base = Dbbackup::where('id', $id)->first();
        if (!$base) {
            return response()->json(['error' => 'Backup no encontrado'], 404);
        }

        $backupPath = storage_path('app/backups/' . $base->archivo);

        if (!file_exists($backupPath)) {
            return response()->json(['error' => 'Archivo de backup no encontrado'], 404);
        }

        try {
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host     = env('DB_HOST');

            // Escapar ruta y parámetros
            $escapedPath = escapeshellarg($backupPath);
            $escapedUser = escapeshellarg($username);
            $escapedDb   = escapeshellarg($database);
            $escapedHost = escapeshellarg($host);
            $escapedPass = escapeshellarg($password);

            // Construir comando
            $command = "PGPASSWORD={$escapedPass} psql -U {$escapedUser} -h {$escapedHost} -d {$escapedDb} -f {$escapedPath}";

            exec($command, $output, $return_var);

            if ($return_var !== 0) {
                return response()->json(['error' => 'Error al restaurar: ' . implode("\n", $output)], 500);
            }

            return response()->json(['success' => 'Backup restaurado exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al restaurar: ' . $e->getMessage()], 500);
        }

    }

}
