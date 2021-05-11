<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleNotaIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_nota_ingreso', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('nota_ingreso_id')->unsigned();
            $table->foreign('nota_ingreso_id')
                  ->references('id')->on('nota_ingreso')
                  ->onDelete('cascade');

            $table->unsignedInteger('producto_id')->unsigned();
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->string('lote');
           // $table->foreign('lote_id')->references('id')->on('lote_productos')->onDelete('cascade');
            $table->unsignedDecimal('cantidad', 15,2);
            $table->date("fecha_vencimiento");
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
        Schema::dropIfExists('detalle_nota_ingreso');
    }
}
