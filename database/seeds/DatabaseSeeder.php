<?php

use App\Departamento;
use App\Distrito;
use App\Provincia;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(DepartamentoSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(DistritoSeeder::class);

        $user = new User();
        $user->usuario = 'Administrador';
        $user->email = 'admin@ecovalle.com';
        $user->password = bcrypt('admin');
        $user->save();
    }
}
