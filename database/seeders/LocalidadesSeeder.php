<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LocalidadesSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/localidades.csv');

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            DB::table('localidades')->insert([
                'id_localidad' => $row[0],
                'nombre' => $row[1],
                'id_municipio' => $row[2],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        fclose($file);
    }
}
