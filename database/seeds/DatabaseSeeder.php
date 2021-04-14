<?php


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // $this->call(ProductoSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(DistritoSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(TablaSeeder::class);
        // $this->call(TablaDetalleSeeder::class);
        $this->call(ParametroSeeder::class);
        // $this->call(EmpresaSeeder::class);
        $this->call(PermissionsSeeder::class);


    }
}
