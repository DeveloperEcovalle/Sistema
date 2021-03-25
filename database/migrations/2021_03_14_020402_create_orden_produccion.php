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
            
            $table->unsignedInteger('producto_id')->unsigned();
            $table->string('codigo_producto');     
            $table->string('descripcion_producto');
            $table->date('fecha_produccion');  
               
            $table->unsignedDecimal('cantidad', 15,2);

            $table->date('fecha_orden');
            $table->mediumText('observacion')->nullable();
            $table->enum('estado',['PRODUCIDO','ANULADO'])->default('PRODUCIDO');
            $table->enum('conformidad',['0','1', '2'])->default('0');
            $table->enum('editable',['0','1', '2'])->default('0');
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
