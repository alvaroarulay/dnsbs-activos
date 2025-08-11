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
        Schema::create('cla_depts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('codigo')->nullable();
            $table->string('desc')->nullable(); 
            $table->string('sigla')->nullable();
            $table->timestamps();
        });
        DB::table('cla_depts')->insert(array('id'=>1,'codigo'=>4,'desc'=>'CHUQUISACA','sigla'=>'CHQ'));
        DB::table('cla_depts')->insert(array('id'=>2,'codigo'=>1,'desc'=>'LA PAZ','sigla'=>'LPZ'));
        DB::table('cla_depts')->insert(array('id'=>3,'codigo'=>3,'desc'=>'COCHABAMBA','sigla'=>'CBB'));
        DB::table('cla_depts')->insert(array('id'=>4,'codigo'=>5,'desc'=>'ORURO','sigla'=>'ORU'));
        DB::table('cla_depts')->insert(array('id'=>5,'codigo'=>6,'desc'=>'POTOSI','sigla'=>'PTS'));
        DB::table('cla_depts')->insert(array('id'=>6,'codigo'=>7,'desc'=>'TARIJA','sigla'=>'TAR'));
        DB::table('cla_depts')->insert(array('id'=>7,'codigo'=>2,'desc'=>'SANTA CRUZ','sigla'=>'SCZ'));
        DB::table('cla_depts')->insert(array('id'=>8,'codigo'=>8,'desc'=>'BENI','sigla'=>'BEN'));
        DB::table('cla_depts')->insert(array('id'=>9,'codigo'=>9,'desc'=>'PANDO','sigla'=>'PAN'));
        DB::table('cla_depts')->insert(array('id'=>10,'codigo'=>10,'desc'=>'OTROS','sigla'=>'OTR'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cla_depts');
    }
};
