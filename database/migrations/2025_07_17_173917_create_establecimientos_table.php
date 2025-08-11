<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('sigla')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('idunidad')->unsigned();
            $table->foreign('idunidad')->references('id')->on('unidadadmin');
            $table->timestamps();
        });
     DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'Hospital Policial N° 1 "Virgen de Copacabana"','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'B','descripcion'=>'CLINICA DENTAL "CNL. ROBERTO QUINTANILLA"','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'C','descripcion'=>'DIRECCION NACIONAL DE SALUD Y BIENESTAR SOCIAL','idunidad'=>1));
DB::table('establecimientos')->insert(array('sigla'=>'D','descripcion'=>'Jefatura de Bienestar Social','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'E','descripcion'=>'Distrito Policial N° 1','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'F','descripcion'=>'Distrito Policial N° 2','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'G','descripcion'=>'POLICONSULTORIO EL ALTO DIST. POL.  No. 3','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'H','descripcion'=>'JEFATURA REGIONAL DE LA ZONA SUR','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'I','descripcion'=>'Distrito Policial N° 5','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'J','descripcion'=>'U.T.O.P.','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'K','descripcion'=>'Gabinete Transito L. P.','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'L','descripcion'=>'CONSULTORIO MEDICO CMDO DEPARTAMENTAL','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'M','descripcion'=>'Bomberos','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'N','descripcion'=>'Radio Patrullas 110','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'O','descripcion'=>'Academia Nacional de Policias (ANAPOL)','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'P','descripcion'=>'Asociacion Nal. Suboficiales Cabos y Policias (ANSCAPOL)','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'Q','descripcion'=>'Comando Departamental El Alto','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'R','descripcion'=>'Batallon de Transito','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'S ','descripcion'=>'F.E.L.C.C.','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'T','descripcion'=>'ESCUELA BASICA DE POLICIAS (ESBAPOL)','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'w','descripcion'=>'Gabinete Transito El Alto','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'x','descripcion'=>'POLIVALENTES','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'y','descripcion'=>'CENTRO INFANTIL POLICIAL GUARDERIA  VIRGEN DE COPACABANA','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD SANTA CRUZ','idunidad'=>8));
DB::table('establecimientos')->insert(array('sigla'=>'B','descripcion'=>'Gabinete medico transito Montero','idunidad'=>8));
DB::table('establecimientos')->insert(array('sigla'=>'C','descripcion'=>'Gabinete medico transito Santa Cruz','idunidad'=>8));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD CBBA','idunidad'=>3));
DB::table('establecimientos')->insert(array('sigla'=>'B','descripcion'=>'CLINICA DENTAL CBBA.','idunidad'=>3));
DB::table('establecimientos')->insert(array('sigla'=>'C','descripcion'=>'GABINETE MEDICO CBBA.','idunidad'=>3));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD SUCRE','idunidad'=>9));
DB::table('establecimientos')->insert(array('sigla'=>'V','descripcion'=>'GABINETE MEDICO  VIACHA','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPARTAMENTAL DE SALUD POTOSI','idunidad'=>7));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD TARIJA','idunidad'=>10));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD BENI','idunidad'=>2));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPTAL DE SALUD PANDO','idunidad'=>6));
DB::table('establecimientos')->insert(array('sigla'=>'Z','descripcion'=>'CONSULTORIO MEDICO DEL COMANDO GENERAL','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'A-A','descripcion'=>'COMANDO DEPARTAMENTAL','idunidad'=>4));
DB::table('establecimientos')->insert(array('sigla'=>'A','descripcion'=>'DIRECCION DEPARTAMENTAL DE SALUD ORURO','idunidad'=>5));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establecimientos');
    }
};
