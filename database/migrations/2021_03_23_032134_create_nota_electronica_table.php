<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotaElectronicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nota_electronica', function (Blueprint $table) {
            $table->Increments('id');
            
            $table->unsignedInteger('documento_id');
            $table->foreign('documento_id')->references('id')->on('cotizacion_documento')->onDelete('cascade');

            $table->string('tipDocAfectado');
            $table->string('numDocfectado');
            $table->string('codMotivo');
            $table->string('desMotivo');

            $table->string('tipoDoc');
            $table->dateTime('fechaEmision')->nullable();
            $table->string('tipoMoneda')->default('PEN');
           

            //EMPRESA
            $table->BigInteger('ruc_empresa');
            $table->string('empresa');
            $table->mediumText('direccion_fiscal_empresa');
            $table->unsignedInteger('empresa_id'); //OBTENER NUMERACION DE LA EMPRESA 
            //CLIENTE
            $table->string('cod_tipo_documento_cliente');
            $table->string('tipo_documento_cliente');
            $table->BigInteger('documento_cliente');
            $table->mediumText('direccion_cliente');
            $table->string('cliente');


            $table->enum('sunat',['0','1','2'])->default('0');
            $table->enum('tipo_nota',['0','1']);

            $table->BigInteger('correlativo')->nullable();
            $table->string('serie')->nullable();

            $table->string('ruta_comprobante_archivo')->nullable();
            $table->string('nombre_comprobante_archivo')->nullable();


            $table->unsignedDecimal('mtoOperGravadas', 15, 2);
            $table->unsignedDecimal('mtoIGV', 15, 2);
            $table->unsignedDecimal('totalImpuestos', 15, 2);
            $table->unsignedDecimal('mtoImpVenta', 15, 2);
            $table->string('ublVersion')->default('2.1');

            //PARA LA NOTA CREDITO
            $table->string('guia_tipoDoc')->nullable();
            $table->string('guia_nroDoc')->nullable();

            //LEYENDA
            $table->string('code');
            $table->mediumText('value');

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
        Schema::dropIfExists('nota_electronica');
    }
}
