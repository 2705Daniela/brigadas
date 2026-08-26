<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brigada;

class BrigadaSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = ['Primavera', 'Verano', 'Otoño'];

        foreach ($periodos as $periodo) {
            Brigada::create([
                'periodo' => $periodo,
                'municipios' => json_encode([]),
            ]);
        }
    }
}

