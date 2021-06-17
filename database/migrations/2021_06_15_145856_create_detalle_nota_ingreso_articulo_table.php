<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleNotaIngresoArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_nota_ingreso_articulo', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('nota_ingreso_articulo_id')->unsigned();
            $table->foreign('nota_ingreso_articulo_id')
                  ->references('id')->on('nota_ingreso_articulo')
                  ->onDelete('cascade');
            $table->unsignedInteger('articulo_id')->unsigned();
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
            $table->string('lote');
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
        Schema::dropIfExists('detalle_nota_ingreso_articulo');
    }
}
