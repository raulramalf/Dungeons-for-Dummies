<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clase;
use App\Services\DndApiService; 

class ClasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DndApiService $dnd): void
    {   
        foreach ($dnd->getClases() as $item) {
            $detalle = $dnd->getClase($item['index']);
            Clase::create([
                'nombre'                  => $detalle['name'],
                'dado_golpe'              => 'd' . $detalle['hit_die'],
                'competencias_armadura'   => $detalle['proficiencies'] ?? null,
                'habilidad_principal'     => $detalle['saving_throws'][0]['name'] ?? null,
                'puntos_golpe_nivel_1'    => $detalle['hit_die'],
            ]);
        }
    }
}
