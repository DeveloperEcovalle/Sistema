<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->Increments('id');
            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->unsignedInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->dateTime('fecha_documento');
            $table->dateTime('fecha_atencion')->nullable();

            $table->unsignedDecimal('sub_total', 15, 2);
            $table->unsignedDecimal('total_igv', 15, 2);
            $table->unsignedDecimal('total', 15, 2);
            $table->unsignedInteger('user_id');
            $table->string('igv_check',2)->nullable();
            $table->char('igv',3)->nullable();

            $table->string('moneda');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('estado',['VIGENTE','ATENDIDA', 'ANULADO', 'VENCIDA'])->default('VIGENTE');
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
        Schema::dropIfExists('cotizaciones');
    }
}
