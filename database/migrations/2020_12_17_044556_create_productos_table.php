<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('codigo', 50);
            $table->string('nombre');
            $table->mediumText('descripcion')->nullable();
            $table->unsignedInteger('familia_id');
            $table->foreign('familia_id')->references('id')->on('familias')->onDelete('cascade');
            $table->unsignedInteger('sub_familia_id');
            $table->foreign('sub_familia_id')->references('id')->on('subfamilias')->onDelete('cascade');
            $table->string('medida');
            $table->string('linea_comercial');
            $table->string('codigo_barra')->nullable();
            // $table->string('moneda');
            $table->unsignedDecimal('stock', 15, 2)->default(0);
            $table->unsignedDecimal('stock_minimo', 15, 2);
            $table->unsignedDecimal('precio_venta_minimo', 15, 2);
            $table->unsignedDecimal('precio_venta_maximo', 15, 2);
            $table->unsignedDecimal('peso_producto', 15, 2);
            $table->boolean('igv');
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
        Schema::dropIfExists('productos');
    }
}
