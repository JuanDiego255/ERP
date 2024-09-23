<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCxcToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('revenues', function (Blueprint $table) {
            //
            $table->decimal('tasa')->nullable();
            $table->string('cuota')->nullable();
            $table->string('plazo')->nullable();
            $table->tinyInteger('tipo_prestamo')->nullable();
            $table->tinyInteger('moneda')->nullable();
            $table->unsignedBigInteger('plan_venta_id')->nullable();
            $table->foreign('plan_venta_id')->references('id')->on('plan_ventas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('revenues', function (Blueprint $table) {
            //
        });
    }
}
