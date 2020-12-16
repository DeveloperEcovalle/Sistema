<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->Increments('id');
            $table->bigInteger('ruc')->nullable();
            $table->bigInteger('dni')->nullable();
            $table->char('tipo_documento',3);
            $table->string('tipo_persona');
            $table->string('descripcion');
            $table->mediumText('direccion')->nullable();
            
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('web')->nullable();
            $table->string('zona')->nullable();

            $table->string('contacto')->nullable();
            $table->string('celular_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();

            $table->string('transporte')->nullable();
            $table->mediumText('direccion_transporte')->nullable();

            $table->mediumText('direccion_almacen')->nullable();

            $table->string('calidad')->nullable();
            $table->string('celular_calidad')->nullable();
            $table->string('telefono_calidad')->nullable();
            $table->string('correo_calidad')->nullable();

            $table->boolean('activo');
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
        Schema::dropIfExists('proveedores');
    }
}
