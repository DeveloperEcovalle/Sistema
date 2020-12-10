<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id');
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->string('area');
            $table->string('profesion');
            $table->string('cargo');
            $table->string('telefono_referencia');
            $table->string('contacto_referencia');
            $table->string('grupo_sanguineo')->nullable();
            $table->string('alergias')->nullable();
            $table->unsignedTinyInteger('numero_hijos');
            $table->unsignedDecimal('sueldo', 15,2);
            $table->unsignedDecimal('sueldo_bruto', 15,2);
            $table->unsignedDecimal('sueldo_neto', 15,2);
            $table->string('moneda_sueldo');
            $table->string('tipo_banco');
            $table->string('numero_cuenta', 20);
            $table->date('fecha_inicio_actividad');
            $table->date('fecha_fin_actividad')->nullable();
            $table->date('fecha_inicio_planilla');
            $table->date('fecha_fin_planilla')->nullable();
            $table->string('ruta_imagen')->nullable();
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('empleados');
    }
}
