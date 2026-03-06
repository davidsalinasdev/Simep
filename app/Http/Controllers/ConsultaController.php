<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidato;
use App\Models\Partido;
use App\Models\Cargo;
use App\Models\Mesa;
use App\Models\Recinto;

class ConsultaController extends Controller
{
    public function index()
    {

        $recintos = Recinto::with('municipio')->get();

        return view('recintos.index', compact('recintos'));
    }


    public function consulta()
    {

        $partidos = Partido::all();
        $cargos = Cargo::all();
        $mesa = Mesa::all();

        return view('consultas.consulta', compact('partidos', 'cargos', 'mesa'));
    }

    public function store(Request $request)
    {

        Candidato::create([
            'nombre' => $request->nombre,
            'id_partido' => $request->id_partido,
            'id_cargo' => $request->id_cargo,
            'id_municipio' => $request->id_municipio
        ]);

        return redirect('/dashboard');
    }
}
