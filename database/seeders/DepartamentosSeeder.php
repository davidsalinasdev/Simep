<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartamentosSeeder extends Seeder
{
    public function run()
    {
        DB::table('departamentos')->insert([
            [
                'id_departamento' => 1,
                'nombre' => 'Beni',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 2,
                'nombre' => 'Chuquisaca',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 3,
                'nombre' => 'Cochabamba',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 4,
                'nombre' => 'La Paz',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 5,
                'nombre' => 'Oruro',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 6,
                'nombre' => 'Pando',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 7,
                'nombre' => 'Potosí',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 8,
                'nombre' => 'Santa Cruz',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_departamento' => 9,
                'nombre' => 'Tarija',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
