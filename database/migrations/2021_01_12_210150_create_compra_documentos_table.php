<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompraDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_emision');
            $table->date('fecha_entrega');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

            $table->unsignedInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
            
            $table->BigInteger('orden_compra')->nullable();
            
            $table->string('modo_compra');
            $table->string('tipo_compra');
            $table->string('tipo_pago')->nullable();
            $table->string('moneda');

            $table->string('igv_check',2)->nullable();
            $table->char('igv',3)->nullable();
            $table->unsignedDecimal('tipo_cambio', 15,2)->nullable();
            $table->mediumText('observacion')->nullable();
            $table->BigInteger('usuario_id');

            $table->enum('estado',['ACTIVO','PAGADA','ANULADO'])->default('ACTIVO');
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
        Schema::dropIfExists('compra_documentos');
    }
}
