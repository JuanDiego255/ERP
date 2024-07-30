<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telephone');
            $table->string('celular');
            $table->string('email');
            $table->tinyInteger('status');
            $table->string('salario_base');
            $table->string('asociacion')->nullable();
            $table->string('ccss');
            $table->string('tipo_pago');
            $table->string('moneda_pago');
            $table->string('salario_hora');
            $table->string('puesto')->default('vendedor');
            $table->string('comision_ventas');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
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
        Schema::dropIfExists('employees');
    }
}
