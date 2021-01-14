<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('documento', 25);
            $table->mediumText('nombre')->nullable();
            $table->char('departamento_id', 2)->nullable();
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');
            $table->integer('tabladetalles_id')->unsigned()->nullable();
            $table->foreign('tabladetalles_id')->references('id')->on('tabladetalles')->onDelete('cascade');
            $table->char('provincia_id', 4)->nullable();
            $table->foreign('provincia_id')->references('id')->on('provincias')->onDelete('cascade');
            $table->char('distrito_id', 6)->nullable();
            $table->foreign('distrito_id')->references('id')->on('distritos')->onDelete('cascade');
            $table->string('direccion');
            $table->string('correo_electronico')->nullable();
            $table->string('telefono_movil');
            $table->string('telefono_fijo')->nullable();
            $table->string('moneda_credito')->nullable();
            $table->unsignedDecimal('limite_credito', 15,2)->nullable();
            $table->string('nombre_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();
            $table->string('correo_electronico_contacto')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
