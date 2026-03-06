<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidato;
use App\Models\Partido;
use App\Models\Cargo;

class CandidatoController extends Controller
{

    public function create()
    {

        $partidos = Partido::all();
        $cargos = Cargo::all();

        return view('candidatos.create', compact('partidos', 'cargos'));
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
