<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_revenues', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('revenue_id');
            $table->string('cuota')->nullable();
            $table->string('monto_general')->nullable();
            $table->string('monto_intereses')->nullable();
            $table->string('paga')->nullable();
            $table->string('interes_c')->nullable();
            $table->string('amortiza')->nullable();
            $table->tinyInteger('estado')->nullable();
            $table->string('detalle')->nullable();
            $table->string('referencia')->nullable();
            $table->string('cheque')->nullable();
            $table->foreign('revenue_id')->references('id')->on('revenues')->onDelete('cascade');
            $table->timestamp('fecha_interes')->nullable();
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
        Schema::dropIfExists('payment_revenues');
    }
}
