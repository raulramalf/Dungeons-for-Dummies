<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario; // 👈 esta línea es la que falta

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre'   => 'Admin',
            'email'    => 'admin@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'admin',
        ]);

        Usuario::create([
            'nombre'   => 'Dungeon Master',
            'email'    => 'dm@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'dungeon_master',
        ]);

        Usuario::create([
            'nombre'   => 'Jugador 1',
            'email'    => 'jugador@dnd.com',
            'password' => bcrypt('password'),
            'rol'      => 'jugador',
        ]);
    }
}