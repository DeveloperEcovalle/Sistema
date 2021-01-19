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
            $table->integer('tabladetalles_id')->unsigned()->nullable();
            $table->foreign('tabladetalles_id')->references('id')->on('tabladetalles')->onDelete('cascade');
            $table->char('departamento_id', 2)->nullable();
            $table->foreign('departamento_id')->references('id')->on('departamentos')->onDelete('cascade');
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

            $table->string('direccion_negocio');
            $table->date('fecha_aniversario')->nullable();
            $table->text('observaciones')->nullable();
            $table->mediumText('nombre1')->nullable();
            $table->date('fecha_nacimiento1')->nullable();
            $table->string('correo_electronico1')->nullable();
            $table->string('celular1')->nullable();
            $table->mediumText('nombre2')->nullable();
            $table->date('fecha_nacimiento2')->nullable();
            $table->string('correo_electronico2')->nullable();
            $table->string('celular2')->nullable();
            $table->mediumText('nombre3')->nullable();
            $table->date('fecha_nacimiento3')->nullable();
            $table->string('correo_electronico3')->nullable();
            $table->string('celular3')->nullable();
            $table->string('condicion_reparto')->nullable();
            $table->string('direccion_entrega')->nullable();
            $table->string('empresa_envio')->nullable();
            $table->string('pago_flete_envio')->nullable();
            $table->string('persona_recoge')->nullable();
            $table->string('dni_persona_recoge')->nullable();
            $table->string('telefono_dato_envio')->nullable();
            $table->mediumText('dato_envio_observacion')->nullable();


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
