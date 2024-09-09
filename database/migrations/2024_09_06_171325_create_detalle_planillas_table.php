<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePlanillasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_planillas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planilla_id');
            $table->string('salario_base');
            $table->string('bonificacion');
            $table->string('comisiones');
            $table->string('cant_hora_extra');
            $table->string('monto_hora_extra');
            $table->string('adelantos');
            $table->string('prestamos');
            $table->string('asociacion');
            $table->string('total');
            $table->string('observaciones');
            $table->string('deudas');
            $table->string('rebajados');
            $table->string('total_ccss');
            $table->string('vacaciones');
            $table->foreign('planilla_id')->references('id')->on('planillas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_planillas');
    }
}
