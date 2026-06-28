<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // 👈 esta línea es la que falta

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nombre'   => 'Admin',
            'email'    => 'admin@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'admin',
        ]);

        User::create([
            'nombre'   => 'Dungeon Master',
            'email'    => 'dm@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'dungeon_master',
        ]);

        User::create([
            'nombre'   => 'Jugador 1',
            'email'    => 'jugador@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'jugador',
        ]);
    }
}