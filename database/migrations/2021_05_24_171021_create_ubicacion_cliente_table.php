<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicacion_cliente', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('nombre')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullabe();
            $table->string('direccion')->nullabe();
            $table->string('ver',2);
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
        Schema::dropIfExists('ubicacion_cliente');
    }
}
