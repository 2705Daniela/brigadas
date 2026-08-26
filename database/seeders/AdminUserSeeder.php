<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dvazquezo@ipn.mx'],
            [
                'name' => 'Daniela Abigail Vazquez Oran',
                'password' => '123456', 
                'role' => 'Administrador'
            ]
        );
    }
}

