<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->unsignedInteger('articulo_id');
            $table->foreign('articulo_id')->references('id')->on('articulos')->onDelete('cascade');
            $table->unsignedInteger('cantidad');
            $table->unsignedDecimal('peso', 15, 2);
            $table->mediumText('observacion')->nullable();
            $table->enum('estado',['ACTIVO','ANULADO'])->default('ACTIVO');
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
        Schema::dropIfExists('producto_detalles');
    }
}
