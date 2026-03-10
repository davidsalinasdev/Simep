<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecintosSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/recintos.csv');

        if (!file_exists($path)) {
            echo "Archivo CSV no encontrado";
            return;
        }

        $file = fopen($path, 'r');

        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            DB::table('recintos')->insert([
                'id_recinto' => $row[0],
                'nombre' => $row[1],
                'direccion' => $row[2],
                'id_localidad' => $row[3],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        fclose($file);
    }
}
