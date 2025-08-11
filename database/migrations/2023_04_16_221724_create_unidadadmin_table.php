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
        Schema::create('unidadadmin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('entidad')->nullable();
            $table->string('unidad')->nullable();
            $table->string('descrip')->nullable();
            $table->string('ciudad')->nullable();
            $table->integer('estadouni')->nullable();
            $table->integer('idciudad')->unsigned();
            $table->foreign('idciudad')->references('id')->on('cla_depts');
            $table->timestamps();
        });
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSNAL','descrip'=>'DIRECCION NACIONAL DE SALUD','ciudad'=>'DEPTO DE LA PAZ','estadouni'=>1,'idciudad'=>2));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSBEN','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD BENI','ciudad'=>'DEPTO DE BENI','estadouni'=>1,'idciudad'=>8));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSCBA','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD COCHABAMBA','ciudad'=>'DEPTO DE COCHABAMBA','estadouni'=>1,'idciudad'=>3));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSLPZ','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD LA PAZ','ciudad'=>'DEPTO DE LA PAZ','estadouni'=>1,'idciudad'=>2));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSORU','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD ORURO','ciudad'=>'DEPTO DE ORURO','estadouni'=>1,'idciudad'=>4));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSPAN','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD PANDO','ciudad'=>'DEPTO DE PANDO','estadouni'=>1,'idciudad'=>9));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSPOT','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD POTOSI','ciudad'=>'DEPTO DE POTOSI','estadouni'=>1,'idciudad'=>5));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSSCZ','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD SANTA CRUZ','ciudad'=>'DEPTO DE SANTA CRUZ','estadouni'=>1,'idciudad'=>7));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSSUC','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD SUCRE','ciudad'=>'DEPTO DE SUCRE','estadouni'=>1,'idciudad'=>1));
            DB::table('unidadadmin')->insert(array('entidad'=>'0015','unidad'=>'DSTAR','descrip'=>'DIRECCION DEPARTAMENTAL DE SALUD TARIJA','ciudad'=>'DEPTO DE TARIJA','estadouni'=>1,'idciudad'=>6));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unidadadmin');
    }
};
