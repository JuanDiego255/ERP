<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('numero',60);
            $table->unsignedInteger('vehiculo_venta_id');
            $table->foreign('vehiculo_venta_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('vehiculo_venta_id_dos')->nullable();
            $table->foreign('vehiculo_venta_id_dos')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('vehiculo_recibido_id')->nullable();
            $table->foreign('vehiculo_recibido_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('vehiculo_recibido_id_dos')->nullable();
            $table->foreign('vehiculo_recibido_id_dos')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->unsignedInteger('fiador_id')->nullable();
            $table->foreign('fiador_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->foreign('vendedor_id')->references('id')->on('employees')->onDelete('cascade');
            $table->integer('business_id')->unsigned();
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->string('fecha_plan',30);
            $table->tinyInteger('tipo_plan');
            $table->string('gastos_plan',100)->nullable();
            $table->text('desc_financiamiento')->nullable();
            $table->string('desc_forma_pago',255)->nullable();
            $table->string('total_recibido',100)->nullable();
            $table->string('total_financiado',100)->nullable();
            $table->string('monto_recibo',100)->nullable();
            $table->string('monto_efectivo',100)->nullable();     
            $table->string('venta_sin_rebajos',100)->nullable();           
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
        Schema::dropIfExists('plan_ventas');
    }
}
