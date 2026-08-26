<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CodigoPostal;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class CodigoPostalSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/codigos_postales.csv');

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); 

        $rows = iterator_to_array($csv);

        $chunks = array_chunk($rows, 1000);

        foreach ($chunks as $index => $chunk) {
            $data = [];

            foreach ($chunk as $row) {
                if (!isset($row['d_codigo'], $row['d_asenta'], $row['D_mnpio'], $row['d_estado'])) {
                    continue;
                }

                $data[] = [
                    'codigo' => str_pad(trim($row['d_codigo']), 5, '0', STR_PAD_LEFT),
                    'asentamiento' => trim($row['d_asenta']),
                    'municipio' => trim($row['D_mnpio']),
                    'estado'    => trim($row['d_estado']),
                ];
            }
            DB::table('codigos_postales')->insert($data);

            $this->command->info("Bloque ".($index + 1)." insertado (".count($data)." registros)");
        }

        $this->command->info('Importación de códigos postales completada correctamente.');
    }
}
