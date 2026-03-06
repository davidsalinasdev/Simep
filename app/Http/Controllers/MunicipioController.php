<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipio;
use App\Models\Provincia;

class MunicipioController extends Controller
{

    public function index()
    {

        $municipios = Municipio::with('provincia')->get();

        return view('municipios.index', compact('municipios'));
    }

    public function create()
    {

        $provincias = Provincia::all();

        return view('municipios.create', compact('provincias'));
    }

    public function store(Request $request)
    {

        Municipio::create([
            'nombre' => $request->nombre,
            'id_provincia' => $request->id_provincia
        ]);

        return redirect('/municipios');
    }
}
