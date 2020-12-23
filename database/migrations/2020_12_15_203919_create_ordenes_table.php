<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('empresa_id')->unsigned();
            $table->foreign('empresa_id')
                  ->references('id')->on('empresas')
                  ->onDelete('cascade');

            $table->unsignedInteger('proveedor_id')->unsigned();
            $table->foreign('proveedor_id')
                ->references('id')->on('proveedores')
                ->onDelete('cascade');

            $table->date('fecha_documento');
            $table->date('fecha_entrega');

            $table->string('modo_compra');
            $table->string('moneda');
            $table->string('igv_check',2)->nullable();
            $table->char('igv',3)->nullable();
            $table->unsignedDecimal('tipo_cambio', 15,2)->nullable();
            $table->mediumText('observacion')->nullable();
            $table->boolean('enviado')->nullable(); 
            
            $table->enum('estado',['VIGENTE','PENDIENTE','ADELANTO','CONCRETADA','ANULADO'])->default('VIGENTE');

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
        Schema::dropIfExists('ordenes');
    }
}
