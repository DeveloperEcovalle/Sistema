<?php

use Illuminate\Database\Seeder;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->usuario = 'Administrador';
        $user->email = 'admin@ecovalle.com';
        $user->password = bcrypt('admin');
        $user->save();
    }
}
