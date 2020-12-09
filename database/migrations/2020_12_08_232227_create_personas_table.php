<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('documento', 25);
            $table->char('codigo_verificacion', 1)->nullable();
            $table->string('nombres', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100);
            $table->date('fecha_nacimiento');
            $table->string('sexo');
            $table->string('estado_civil')->nullable();
            $table->char('codigo_ubigeo', 6)->nullable();
            $table->string('direccion')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('telefono_movil', 50)->nullable();
            $table->string('telefono_fijo', 50)->nullable();
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
        Schema::dropIfExists('personas');
    }
}
