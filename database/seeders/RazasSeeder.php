<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Raza; 
use App\Services\DndApiService; 

class RazasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DndApiService $dnd): void
    {
        foreach ($dnd->getRazas() as $item) {
            $detalle = $dnd->getRaza($item['index']);
            Raza::create([
                'nombre'                      => $detalle['name'],
                'velocidad'                   => $detalle['speed'],
                'tamaño'                      => $detalle['size'],
                'bonificadores_caracteristica' => $detalle['ability_bonuses'],
                'idiomas'                     => array_column($detalle['languages'], 'name'),
                'rasgos'                      => array_column($detalle['traits'], 'name'),
            ]);
        }
    }
}
