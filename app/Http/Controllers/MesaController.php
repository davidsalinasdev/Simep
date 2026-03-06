<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use App\Models\Recinto;

class MesaController extends Controller
{

    public function index()
    {
        $mesas = Mesa::with('recinto')->get();

        return view('mesas.index', compact('mesas'));
    }

    public function create()
    {
        $recintos = Recinto::all();

        return view('mesas.create', compact('recintos'));
    }

    public function store(Request $request)
    {
        Mesa::create([
            'numero_mesa' => $request->numero_mesa,
            'id_recinto' => $request->id_recinto,
            'estado' => 'pendiente'
        ]);

        return redirect('/mesas');
    }
}
