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
        Schema::create('codcont', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('codcont')->nullable();
            $table->string('codigo')->nullable();
            $table->string('nombre')->nullable();
            $table->integer('vidautil')->nullable();
            $table->string('observ')->nullable(); 
            $table->boolean('depreciar')->nullable(); 
            $table->boolean('actualizar')->nullable(); 
            $table->timestamps();
        });
        DB::table('codcont')->insert(array('id'=>1,'codcont'=>1,'codigo'=>'','nombre'=>'EDIFICACIONES','vidautil'=>40,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>2,'codcont'=>2,'codigo'=>'431.1','nombre'=>'MUEBLES Y ENSERES DE OFICINA','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>3,'codcont'=>3,'codigo'=>'437','nombre'=>'MAQUINARIA EN GENERAL','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>4,'codcont'=>4,'codigo'=>'434','nombre'=>'EQUIPO MEDICO Y DE LABORATORIO','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>5,'codcont'=>5,'codigo'=>'435','nombre'=>'EQUIPO DE COMUNICACIONES','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>6,'codcont'=>6,'codigo'=>'435','nombre'=>'EQUIPO EDUCACIONAL Y RECREATIVO','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>7,'codcont'=>7,'codigo'=>'','nombre'=>'BARCOS Y LANCHAS EN GENERAL','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>8,'codcont'=>8,'codigo'=>'433','nombre'=>'VEHICULOS AUTOMOTORES','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>9,'codcont'=>9,'codigo'=>'','nombre'=>'AVIONES','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>10,'codcont'=>10,'codigo'=>'','nombre'=>'MAQUINARIA PARA LA CONSTRUCCION','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>11,'codcont'=>11,'codigo'=>'','nombre'=>'MAQUINARIA AGRICOLA','vidautil'=>4,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>12,'codcont'=>12,'codigo'=>'','nombre'=>'ANIMALES DE TRABAJO','vidautil'=>4,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>13,'codcont'=>13,'codigo'=>'','nombre'=>'HERRAMIENTAS EN GENERAL','vidautil'=>4,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>14,'codcont'=>14,'codigo'=>'','nombre'=>'REPRODUCTORES Y HEMBRAS DE PEDIGREE','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>15,'codcont'=>15,'codigo'=>'431.2','nombre'=>'EQUIPOS DE COMPUTACION','vidautil'=>4,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>16,'codcont'=>16,'codigo'=>'','nombre'=>'CANALES DE REGADIO Y POZOS','vidautil'=>20,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>17,'codcont'=>17,'codigo'=>'','nombre'=>'ESTANQUES, BA¥ADEROS Y ABREVADEROS','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>18,'codcont'=>18,'codigo'=>'','nombre'=>'ALAMBRADOS, TRANQUERAS Y VALLAS','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>19,'codcont'=>19,'codigo'=>'','nombre'=>'VIVIENDAS PARA EL PERSONAL','vidautil'=>20,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>20,'codcont'=>20,'codigo'=>'','nombre'=>'MUEBLES Y ENSERES EN VIVIENDAS DE PERSONAL','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>21,'codcont'=>21,'codigo'=>'','nombre'=>'SILOS, ALMACENES Y GALPONES','vidautil'=>20,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>22,'codcont'=>22,'codigo'=>'','nombre'=>'TINGLADOS Y COBERTIZOS DE MADERA','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>23,'codcont'=>23,'codigo'=>'','nombre'=>'TINGLADOS Y COBERTIZOS DE METAL','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>24,'codcont'=>24,'codigo'=>'','nombre'=>'INSTALACION DE ELECTRIFICACION Y TELEFONIA RURAL','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>25,'codcont'=>25,'codigo'=>'','nombre'=>'CAMINOS INTERIORES','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>26,'codcont'=>26,'codigo'=>'','nombre'=>'CA¥A DE AZUCAR','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>27,'codcont'=>27,'codigo'=>'','nombre'=>'VIDES','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>28,'codcont'=>28,'codigo'=>'','nombre'=>'FRUTALES','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>29,'codcont'=>29,'codigo'=>'','nombre'=>'LINEAS DE RECOLECCION DE LA INDUSTRIA PETROLERA','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>30,'codcont'=>30,'codigo'=>'','nombre'=>'POZOS PETROLEROS','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>31,'codcont'=>31,'codigo'=>'','nombre'=>'EQUIPOS DE CAMPO DE LA INDUSTRIA PETROLERA','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>32,'codcont'=>32,'codigo'=>'','nombre'=>'PLANTA DE PROCESAMIENTO DE LA INDUSTRIA PETROLERA','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>33,'codcont'=>33,'codigo'=>'','nombre'=>'DUCTOS DE LA INDUSTRIA PETROLERA','vidautil'=>10,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>34,'codcont'=>34,'codigo'=>'470','nombre'=>'TERRENOS','vidautil'=>0,'depreciar'=>0,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>36,'codcont'=>36,'codigo'=>'','nombre'=>'OTROS ACTIVOS FIJOS','vidautil'=>0,'depreciar'=>0,'actualizar'=>0));
        DB::table('codcont')->insert(array('id'=>37,'codcont'=>37,'codigo'=>'490','nombre'=>'ACTIVOS INTANGIBLES','vidautil'=>5,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>38,'codcont'=>38,'codigo'=>'','nombre'=>'EQUIPO E INSTALACIONES','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>39,'codcont'=>39,'codigo'=>'','nombre'=>'OTRAS PLANTACIONES','vidautil'=>8,'depreciar'=>1,'actualizar'=>1));
        DB::table('codcont')->insert(array('id'=>40,'codcont'=>40,'codigo'=>'','nombre'=>'ACTIVOS MUSEOLOGICOS Y CULTURALES','vidautil'=>0,'depreciar'=>0,'actualizar'=>0));


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codcont');
    }
};
