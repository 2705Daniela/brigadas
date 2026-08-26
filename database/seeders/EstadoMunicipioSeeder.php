<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoMunicipioSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/estados_municipios.csv');

        if (!file_exists($file) || !is_readable($file)) {
            $this->command->info('Archivo CSV no encontrado o no legible.');
            return;
        }

        $header = null;
        $data = [];

        if (($handle = fopen($file, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }
                $data[] = [
                    'estado' => $row[0],
                    'municipio' => $row[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);
        }

        // Limpiar la tabla antes de insertar
        DB::table('estados_municipios')->truncate();

        // Insertar todos los registros
        DB::table('estados_municipios')->insert($data);

        $this->command->info('Estados y municipios cargados correctamente.');
    }
}
