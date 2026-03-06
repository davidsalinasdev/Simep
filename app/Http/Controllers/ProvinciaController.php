<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Departamento;

class ProvinciaController extends Controller
{

    public function index()
    {

        $provincias = Provincia::with('departamento')->get();

        return view('provincias.index', compact('provincias'));
    }

    public function create()
    {

        $departamentos = Departamento::all();

        return view('provincias.create', compact('departamentos'));
    }

    public function store(Request $request)
    {

        Provincia::create([
            'nombre' => $request->nombre,
            'id_departamento' => $request->id_departamento
        ]);

        return redirect('/provincias');
    }
}
