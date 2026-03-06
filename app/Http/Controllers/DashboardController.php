<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mesa;

class DashboardController extends Controller
{

    public function index()
    {

        $resultados = DB::table('detalle_resultado')
            ->join('candidatos', 'detalle_resultado.id_candidato', '=', 'candidatos.id_candidato')
            ->join('partidos', 'candidatos.id_partido', '=', 'partidos.id_partido')
            ->select(
                'candidatos.nombre as candidato',
                'partidos.nombre as partido',
                DB::raw('SUM(detalle_resultado.votos) as total_votos')
            )
            ->groupBy('candidatos.nombre', 'partidos.nombre')
            ->orderByDesc('total_votos')
            ->get();

        $mesas_total = Mesa::count();

        $mesas_enviadas = Mesa::where('estado', 'enviado')->count();

        $porcentaje = 0;

        if ($mesas_total > 0) {
            $porcentaje = ($mesas_enviadas / $mesas_total) * 100;
        }

        return view('dashboard', compact(
            'resultados',
            'mesas_total',
            'mesas_enviadas',
            'porcentaje'
        ));
    }
}
