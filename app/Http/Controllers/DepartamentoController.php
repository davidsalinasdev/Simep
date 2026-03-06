<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;

class DepartamentoController extends Controller
{

    public function index()
    {
        $departamentos = Departamento::all();

        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {

        Departamento::create([
            'nombre' => $request->nombre
        ]);

        return redirect('/departamentos');
    }
}
