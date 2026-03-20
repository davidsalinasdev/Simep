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
            'id_mesa' => 'required|exists:mesas,id_mesa',
            'imagen_acta' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
            'votos' => 'required|array'
        ]);

        DB::beginTransaction();

        try {

            /* =========================
        VALIDAR MESA
        ========================= */
            $mesa = DB::table('mesas')
                ->where('id_mesa', $request->id_mesa)
                ->lockForUpdate()
                ->first();

            if (!$mesa) {
                throw new \Exception("Mesa no encontrada");
            }

            if ($request->tipo_eleccion == 'gobernacion') {

                if ($mesa->estado_gobernacion != 'pendiente') {
                    throw new \Exception("Esta mesa ya fue registrada para Gobernador");
                }
            } else {

                if ($mesa->estado_alcaldia != 'pendiente') {
                    throw new \Exception("Esta mesa ya fue registrada para Alcalde");
                }
            }


            /* =========================
        GUARDAR IMAGEN
        ========================= */
            $rutaImagen = null;

            if ($request->hasFile('imagen_acta')) {

                $imagen = $request->file('imagen_acta');
                $nombre = time() . '.jpg';
                $ruta = storage_path('app/public/actas/' . $nombre);

                $manager = new ImageManager(new Driver());

                $manager->read($imagen)
                    ->scale(1200)
                    ->toJpeg(70)
                    ->save($ruta);

                $rutaImagen = 'actas/' . $nombre;
            }

            /* =========================
VALIDAR CONSISTENCIA
========================= */
            $total_votos = array_sum($request->votos);

            $total_especiales = 0;

            if ($request->has('especial')) {
                foreach ($request->especial as $datos) {
                    $total_especiales += intval($datos['blancos'] ?? 0);
                    $total_especiales += intval($datos['nulos'] ?? 0);
                }
            }

            /* =========================
        INSERTAR RESULTADO
        ========================= */
            $id_resultado = DB::table('resultados')->insertGetId([
                'id_mesa' => $request->id_mesa,
                'id_usuario' => Auth::id(),
                'fecha_envio' => now(),
                'imagen_acta' => $rutaImagen,
                'estado_validacion' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now()
            ], 'id_resultado');
            /* =========================
        GUARDAR VOTOS PARTIDOS
        ========================= */
            foreach ($request->votos as $id_partido_cargo => $votos) {

                if ($votos < 0) {
                    throw new \Exception("No se permiten votos negativos");
                }

                DB::table('votos_partido')->insert([
                    'id_resultado' => $id_resultado,
                    'id_partido_cargo' => $id_partido_cargo,
                    'votos' => intval($votos),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            /* =========================
        GUARDAR VOTOS ESPECIALES (PRO)
        ========================= */
            if ($request->has('especial')) {

                foreach ($request->especial as $cargo => $datos) {

                    $nombreCargo = ucwords(str_replace('_', ' ', $cargo));

                    $id_cargo = DB::table('cargos')
                        ->where('nombre_cargo', $nombreCargo)
                        ->value('id_cargo');

                    if (!$id_cargo) {
                        throw new \Exception("Cargo no encontrado: " . $nombreCargo);
                    }

                    // 🚫 Validar negativos
                    if (($datos['blancos'] ?? 0) < 0 || ($datos['nulos'] ?? 0) < 0) {
                        throw new \Exception("Valores negativos no permitidos");
                    }

                    DB::table('votos_especiales')->insert([
                        'id_resultado' => $id_resultado,
                        'id_cargo' => $id_cargo,
                        'blancos' => intval($datos['blancos'] ?? 0),
                        'nulos' => intval($datos['nulos'] ?? 0),
                        // 'total_papeletas' => intval($request->total_papeletas),
                        'tipo_eleccion' => $request->tipo_eleccion,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            /* =========================
        ACTUALIZAR MESA
        ========================= */
            if ($request->tipo_eleccion == 'gobernacion') {

                DB::table('mesas')
                    ->where('id_mesa', $request->id_mesa)
                    ->update([
                        'estado_gobernacion' => 'enviado',
                        'updated_at' => now()
                    ]);
            } else {

                DB::table('mesas')
                    ->where('id_mesa', $request->id_mesa)
                    ->update([
                        'estado_alcaldia' => 'enviado',
                        'updated_at' => now()
                    ]);
            }


            DB::commit();

            return redirect()->back()
                ->with('success', 'Resultados registrados correctamente');
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
