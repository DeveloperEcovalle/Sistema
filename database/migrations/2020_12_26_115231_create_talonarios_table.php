<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalonariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talonarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_documento');
            $table->string('serie');
            $table->unsignedInteger('numero_inicio');
            $table->unsignedInteger('numero_final')->nullable();
            $table->unsignedInteger('numero_actual');
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
        Schema::dropIfExists('talonarios');
    }
}
