<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsuariosSeeder::class,
            PerfilSeeder::class,
            ClasesSeeder::class,
            RazasSeeder::class,
            SubclasesSeeder::class,
            SubclasesEdicion55Seeder::class,
            TrasfondosSeeder::class,
            TrasfondosEdicion55Seeder::class,
            DotesSeeder::class,
            DotesEdicion55Seeder::class,
            ConjurosEdicion55Seeder::class,
        ]);
    }
}