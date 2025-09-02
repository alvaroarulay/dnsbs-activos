<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use XBase\TableReader;
use Illuminate\Support\Facades\DB;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
}