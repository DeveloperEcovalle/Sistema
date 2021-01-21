<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_guias', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('guia_id')->unsigned();
            $table->foreign('guia_id')
                  ->references('id')->on('guias')
                  ->onDelete('cascade');

            $table->unsignedInteger('articulo_id')->unsigned();
            $table->foreign('articulo_id')
                ->references('id')->on('articulos')
                ->onDelete('cascade');
            $table->BigInteger('cantidad_solicitada')->nullable();
            $table->BigInteger('cantidad_entregada')->nullable();
            $table->BigInteger('cantidad_devuelta')->nullable();
            $table->string('observacion')->nullable();
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
        Schema::dropIfExists('detalle_guias');
    }
}
