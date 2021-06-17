<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoNotaArticuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_nota_articulo', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedDecimal('cantidad', 15,2);
            $table->unsignedInteger('nota_id')->unsigned();
            $table->mediumText('observacion');
            $table->unsignedInteger('usuario_id')->unsigned();
            $table->unsignedInteger('lote_id')->unsigned();
            $table->enum('movimiento',['SALIDA','INGRESO']);
            $table->unsignedInteger('articulo_id')->unsigned();
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
        Schema::dropIfExists('movimiento_nota_articulo');
    }
}
