<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidato;
use App\Models\Partido;
use App\Models\Cargo;
use App\Models\Mesa;
use App\Models\Recinto;
use Illuminate\Support\Facades\DB;

class ConsultaController extends Controller
{

    public function index()
    {

        // RESULTADOS POR PARTIDO
        $resultados = DB::table('votos_partido as vp')
            ->join('partido_cargo as pc', 'pc.id', '=', 'vp.id_partido_cargo')
            ->join('partidos as p', 'p.id_partido', '=', 'pc.id_partido')
            ->select(
                'p.nombre',
                'p.sigla',
                'p.color',
                DB::raw('SUM(vp.votos) as total')
            )
            ->groupBy('p.nombre', 'p.sigla', 'p.color')
            ->orderByDesc('total')
            ->get();


        // VOTOS ESPECIALES
        $blancos = DB::table('votos_especiales')->sum('blancos');
        $nulos = DB::table('votos_especiales')->sum('nulos');
        $emitidos = DB::table('votos_especiales')->sum('total_papeletas');


        // VOTOS VALIDOS
        $validos = DB::table('votos_partido')->sum('votos');


        return view('consultas.index', compact(
            'resultados',
            'blancos',
            'nulos',
            'emitidos',
            'validos'
        ));
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
