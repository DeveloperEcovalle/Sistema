<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificarClienteTiendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cliente_tiendas', function (Blueprint $table) {
            $table->string("lat")->nullable();
            $table->string("lng")->nullable();
            $table->string("ruta_logo")->nullable();
            $table->string("nombre_logo")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
