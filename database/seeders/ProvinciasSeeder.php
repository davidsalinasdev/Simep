<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProvinciasSeeder extends Seeder
{
    public function run()
    {
        DB::table('provincias')->insert([

            ['id_provincia' => 1, 'nombre' => 'Arani', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 2, 'nombre' => 'Arque', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 3, 'nombre' => 'Ayopaya', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 4, 'nombre' => 'Bolivar', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 5, 'nombre' => 'Campero', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 6, 'nombre' => 'Capinota', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 7, 'nombre' => 'Carrasco', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 8, 'nombre' => 'Cercado', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 9, 'nombre' => 'Chapare', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 10, 'nombre' => 'Esteban Arze', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 11, 'nombre' => 'Germán Jordán', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 12, 'nombre' => 'Mizque', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 13, 'nombre' => 'Punata', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 14, 'nombre' => 'Quillacollo', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 15, 'nombre' => 'Tapacarí', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_provincia' => 16, 'nombre' => 'Tiraque', 'id_departamento' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]

        ]);
    }
}
