<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleLineaProduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_linea_produccion', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('maquinaria_equipo_id')->unsigned();
            // $table->foreign('maquinaria_equipo_id')
            //     ->references('id')->on('maquinarias_equipos')
            //     ->onDelete('cascade');
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
        Schema::dropIfExists('detalle_linea_produccion');
    }
}
