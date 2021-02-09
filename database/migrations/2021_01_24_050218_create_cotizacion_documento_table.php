<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionDocumentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacion_documento', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            
            $table->dateTime('fecha_documento');
            $table->dateTime('fecha_atencion')->nullable();
            // $table->string('moneda');

            $table->string('tipo_venta');
            $table->unsignedDecimal('sub_total', 15, 2);
            $table->unsignedDecimal('total_igv', 15, 2);
            $table->unsignedDecimal('total', 15, 2);
            $table->string('tipo_pago')->nullable();
            
            $table->string('igv_check',2)->nullable();
            $table->char('igv',3)->nullable();
            $table->string('moneda');

            $table->BigInteger('cotizacion_venta')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->mediumText('observacion')->nullable();
            $table->enum('estado',['VIGENTE','PENDIENTE','ADELANTO','CONCRETADA','ANULADO','PAGADA'])->default('VIGENTE');
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
        Schema::dropIfExists('cotizacion_documento');
    }
}
