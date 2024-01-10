<?php

namespace Database\Seeders;

use App\Models\Proveedores;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //enviamos 15 registros pruebas
        Proveedores::factory()->count(15)->create();
    }
}
