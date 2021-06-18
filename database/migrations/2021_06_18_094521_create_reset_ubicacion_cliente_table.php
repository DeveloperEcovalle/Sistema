<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResetUbicacionClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ubicacion_cliente');
        Schema::create('ubicacion_cliente', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('ver',2);
            $table->unsignedInteger('tienda_id');
            $table->foreign('tienda_id')->references('id')->on('cliente_tiendas')->onDelete('cascade');
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

    }
}
