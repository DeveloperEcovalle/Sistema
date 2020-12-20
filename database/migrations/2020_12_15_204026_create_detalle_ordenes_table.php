<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ordenes', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('orden_id')->unsigned();
            $table->foreign('orden_id')
                  ->references('id')->on('ordenes')
                  ->onDelete('cascade');

            $table->unsignedInteger('articulo_id')->unsigned();
            $table->foreign('articulo_id')
                ->references('id')->on('articulos')
                ->onDelete('cascade');

            $table->BigInteger('cantidad');
            $table->unsignedDecimal('precio', 15,2);


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
        Schema::dropIfExists('detalle_ordenes');
    }
}
