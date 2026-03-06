<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recinto;
use App\Models\Municipio;

class RecintoController extends Controller
{

    public function index()
    {

        $recintos = Recinto::with('municipio')->get();

        return view('recintos.index', compact('recintos'));
    }

    public function create()
    {

        $municipios = Municipio::all();

        return view('recintos.create', compact('municipios'));
    }

    public function store(Request $request)
    {

        Recinto::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'id_municipio' => $request->id_municipio
        ]);

        return redirect('/recintos');
    }
}
