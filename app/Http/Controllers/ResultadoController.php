<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ResultadoController extends Controller
{

    public function store(Request $request)
    {



        $request->validate([
            'id_mesa' => 'required',
            'imagen_acta' => 'nullable|image|mimes:jpg,jpeg,png|max:20480'
        ]);

        DB::beginTransaction();

        try {
            /* VALIDAR MESA */
            $mesa = DB::table('mesas')
                ->where('id_mesa', $request->id_mesa)
                ->first();



            // dd($request->id_mesa);
            if (!$mesa || $mesa->estado != 'pendiente') {
                return redirect()->back()
                    ->with('error', 'Esta mesa ya fue registrada o no existe');
            }


            /* GUARDAR IMAGEN */

            $rutaImagen = null;

            if ($request->hasFile('imagen_acta')) {

                $imagen = $request->file('imagen_acta');

                $nombre = time() . '.jpg';

                $ruta = storage_path('app/public/actas/' . $nombre);

                $manager = new ImageManager(new Driver());

                $image = $manager->read($imagen)
                    ->scale(1200)
                    ->toJpeg(70);

                $image->save($ruta);

                $rutaImagen = 'actas/' . $nombre;
            }

            /* INSERTAR RESULTADO */
            $id_resultado = DB::table('resultados')->insertGetId([

                'id_mesa' => $request->id_mesa,
                'id_usuario' => Auth::id(),
                'fecha_envio' => now(),
                'imagen_acta' => $rutaImagen,
                'estado_validacion' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now()

            ], 'id_resultado'); // 👈 AQUI


            /* GUARDAR VOTOS PARTIDOS */

            foreach ($request->votos as $id_partido_cargo => $votos) {

                DB::table('votos_partido')->insert([

                    'id_resultado' => $id_resultado,
                    'id_partido_cargo' => $id_partido_cargo,
                    'votos' => $votos,
                    'created_at' => now(),
                    'updated_at' => now()

                ]);
            }


            /* GUARDAR VOTOS ESPECIALES */

            DB::table('votos_especiales')->insert([

                'id_resultado' => $id_resultado,
                'blancos' => $request->blancos,
                'nulos' => $request->nulos,
                'total_papeletas' => $request->total_papeletas,
                'tipo_eleccion' => $request->tipo_eleccion,
                'created_at' => now(),
                'updated_at' => now()

            ]);


            /* ACTUALIZAR MESA */

            DB::table('mesas')
                ->where('id_mesa', $request->id_mesa)
                ->update([

                    'estado' => 'enviado',
                    'updated_at' => now()

                ]);


            DB::commit();

            return redirect()->back()
                ->with('success', 'Resultados registrados correctamente');
        } catch (\Exception $e) {

            DB::rollBack();

            // return redirect()->back()
            //     ->with('error', 'Error al guardar resultados');
            dd($e);
        }
    }
}
