<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraDocumentoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_documento_detalles', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('documento_id')->unsigned();
            $table->foreign('documento_id')
                  ->references('id')->on('compra_documentos')
                  ->onDelete('cascade');

            $table->unsignedInteger('articulo_id')->unsigned();
            $table->string('codigo_articulo');
            $table->string('descripcion_articulo');
            $table->string('presentacion_articulo');
            $table->string('medida_articulo');
        
            $table->BigInteger('cantidad');
            $table->date('fecha_vencimiento');
            $table->string('lote');
           
            $table->unsignedDecimal('precio', 15,2);
            $table->unsignedDecimal('costo_flete', 15,2);

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
        Schema::dropIfExists('compra_documento_detalles');
    }
}
