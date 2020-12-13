<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Mantenimiento\Tabla\General;
use App\Mantenimiento\Tabla\Detalle;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(TablaSeeder::class);
        $this->call(TablaDetalleSeeder::class);
        $this->call(ParametroSeeder::class);

    }
}
