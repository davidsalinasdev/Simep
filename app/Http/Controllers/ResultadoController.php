<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Resultado;
use App\Models\DetalleResultado;
use App\Models\VotoEspecial;
use App\Models\Candidato;
use Illuminate\Support\Facades\Auth;

class ResultadoController extends Controller
{

    public function create($id_mesa)
    {

        $mesa = Mesa::findOrFail($id_mesa);

        $candidatos = Candidato::all();

        return view('resultados.create', compact('mesa', 'candidatos'));
    }

    public function store(Request $request)
    {

        $mesa = Mesa::findOrFail($request->id_mesa);

        if ($mesa->estado == 'enviado') {
            return back()->with('error', 'Esta mesa ya fue registrada');
        }

        $suma = 0;

        foreach ($request->votos as $v) {
            $suma += $v;
        }

        $suma_total = $suma + $request->blancos + $request->nulos;

        if ($suma_total != $request->total_papeletas) {
            return back()->with('error', 'La suma de votos no coincide con total de papeletas');
        }

        $ruta = $request->file('imagen_acta')->store('actas', 'public');

        $resultado = Resultado::create([
            'id_mesa' => $mesa->id_mesa,
            'id_usuario' => Auth::user()->id_usuario,
            'imagen_acta' => $ruta,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud
        ]);

        foreach ($request->votos as $id_candidato => $votos) {

            DetalleResultado::create([
                'id_resultado' => $resultado->id_resultado,
                'id_candidato' => $id_candidato,
                'votos' => $votos
            ]);
        }

        VotoEspecial::create([
            'id_resultado' => $resultado->id_resultado,
            'blancos' => $request->blancos,
            'nulos' => $request->nulos,
            'total_papeletas' => $request->total_papeletas
        ]);

        $mesa->estado = 'enviado';
        $mesa->save();

        return redirect('/dashboard')->with('success', 'Resultado registrado');
    }
}
