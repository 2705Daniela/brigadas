<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Crear administrador
        User::updateOrCreate(
            ['email' => 'dvazquezo@ipn.mx'], // Evita duplicados
            [
                'name' => 'Daniela Abigail Vazquez Oran',
                'password' => '123456', // 
                'role' => 'Administrador',
            ]
        );

         $this->call(EstadoMunicipioSeeder::class);
    }
}

