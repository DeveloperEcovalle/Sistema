<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->Increments('id');
            $table->BigInteger('ruc');
            $table->string('razon_social');
            $table->string('razon_social_abreviada')->nullable();
            $table->string('ruta_logo')->nullable();
            $table->string('nombre_logo')->nullable();
            $table->longText('base64_logo')->nullable();

            $table->mediumText('direccion_fiscal');
            $table->string('ubigeo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo')->nullable();

            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('web')->nullable();

            $table->mediumText('direccion_llegada');

            $table->string('dni_representante');
            $table->string('nombre_representante');
            $table->string('num_asiento');
            $table->string('num_partida');
            $table->string('estado_ruc')->default('INACTIVO');
            $table->string('estado_dni_representante')->default('INACTIVO');
            $table->enum('estado',['ACTIVO','ANULADO'])->default('ACTIVO');

            // Facturacion Electronica
            $table->enum('estado_fe',['0','1'])->default('0');


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
        Schema::dropIfExists('empresas');
    }
}
