<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conjuro;
use App\Services\DndApiService; 

class ConjurosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(DndApiService $dnd): void
    {
        foreach ($dnd->getConjuros() as $item) {
            $d = $dnd->getConjuro($item['index']);
            Conjuro::create([
                'nombre'             => $d['name'],
                'nivel'              => $d['level'],
                'escuela'            => $d['school']['name'],
                'tiempo_lanzamiento' => $d['casting_time'],
                'alcance'            => $d['range'],
                'componentes'        => $d['components'],
                'material'           => $d['material'] ?? null,
                'duracion'           => $d['duration'],
                'concentracion'      => $d['concentration'],
                'ritual'             => $d['ritual'],
                'descripcion'        => implode("\n", $d['desc']),
                'clases'             => array_column($d['classes'], 'name'),
            ]);
        }
    }
}
