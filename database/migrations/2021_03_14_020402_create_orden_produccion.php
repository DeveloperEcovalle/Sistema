<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenProduccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_produccion', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('programacion_id')->unsigned();
            $table->foreign('programacion_id')
                  ->references('id')->on('programacion_produccion')
                  ->onDelete('cascade');
            $table->date('fecha_orden');
            $table->mediumText('observacion')->nullable();
            $table->enum('estado',['PRODUCIDO','ANULADO'])->default('PRODUCIDO');
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
        Schema::dropIfExists('orden_produccion');
    }
}
