<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MunicipiosSeeder extends Seeder
{
    public function run()
    {
        DB::table('municipios')->insert([

            ['id_municipio' => 1, 'nombre' => 'Aiquile', 'id_provincia' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 2, 'nombre' => 'Alalay', 'id_provincia' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 3, 'nombre' => 'Anzaldo', 'id_provincia' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 4, 'nombre' => 'Arani', 'id_provincia' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 5, 'nombre' => 'Arbieto', 'id_provincia' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 6, 'nombre' => 'Arque', 'id_provincia' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 7, 'nombre' => 'Ayopaya', 'id_provincia' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 8, 'nombre' => 'Bolivar', 'id_provincia' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 9, 'nombre' => 'Capinota', 'id_provincia' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 10, 'nombre' => 'Chimoré', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 11, 'nombre' => 'Cliza', 'id_provincia' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 12, 'nombre' => 'Cocapata', 'id_provincia' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 13, 'nombre' => 'Cochabamba', 'id_provincia' => 8, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 14, 'nombre' => 'Colcapirhua', 'id_provincia' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 15, 'nombre' => 'Colomi', 'id_provincia' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 16, 'nombre' => 'Entre Ríos', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 17, 'nombre' => 'Mizque', 'id_provincia' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 18, 'nombre' => 'Morochata', 'id_provincia' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 19, 'nombre' => 'Omereque', 'id_provincia' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 20, 'nombre' => 'Pasorapa', 'id_provincia' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 21, 'nombre' => 'Pocona', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 22, 'nombre' => 'Pojo', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 23, 'nombre' => 'Puerto Villarroel', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 24, 'nombre' => 'Punata', 'id_provincia' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 25, 'nombre' => 'Quillacollo', 'id_provincia' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 26, 'nombre' => 'Sacaba', 'id_provincia' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 27, 'nombre' => 'Sacabamba', 'id_provincia' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 28, 'nombre' => 'San Benito', 'id_provincia' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 29, 'nombre' => 'Santivañez', 'id_provincia' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 30, 'nombre' => 'Shinahota', 'id_provincia' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 31, 'nombre' => 'Sicaya', 'id_provincia' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 32, 'nombre' => 'Sipesipe', 'id_provincia' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 33, 'nombre' => 'Tacachi', 'id_provincia' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 34, 'nombre' => 'Tacopaya', 'id_provincia' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 35, 'nombre' => 'Tapacarí', 'id_provincia' => 15, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 36, 'nombre' => 'Tarata', 'id_provincia' => 10, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 37, 'nombre' => 'TIOC Raqaypampa', 'id_provincia' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 38, 'nombre' => 'Tiquipaya', 'id_provincia' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 39, 'nombre' => 'Tiraque', 'id_provincia' => 16, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 40, 'nombre' => 'Toco', 'id_provincia' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 41, 'nombre' => 'Tolata', 'id_provincia' => 11, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 42, 'nombre' => 'Totora', 'id_provincia' => 7, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 43, 'nombre' => 'Vacas', 'id_provincia' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 44, 'nombre' => 'Vila Vila', 'id_provincia' => 12, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 45, 'nombre' => 'Villa Gualberto Villarroel', 'id_provincia' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 46, 'nombre' => 'Villa Rivero', 'id_provincia' => 13, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 47, 'nombre' => 'Villa Tunari', 'id_provincia' => 9, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id_municipio' => 48, 'nombre' => 'Vinto', 'id_provincia' => 14, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]

        ]);
    }
}
