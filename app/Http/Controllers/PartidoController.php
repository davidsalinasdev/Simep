<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partido;

class PartidoController extends Controller
{

    public function index()
    {
        $partidos = Partido::all();
        return view('partidos.index', compact('partidos'));
    }

    public function create()
    {
        return view('partidos.create');
    }

    public function store(Request $request)
    {

        Partido::create([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla,
            'color' => $request->color
        ]);

        return redirect('/partidos');
    }
}
