<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\RecintoController;
use App\Http\Controllers\DelegadoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', function () {

    if (!Auth::check()) {
        return redirect('/login');
    }

    $rol = Auth::user()->rol;

    if ($rol == 'delegado_recinto') {

        return redirect('/delegado/create');
    } elseif ($rol == 'consulta') {

        return redirect('/consultas/consulta');
    } else {

        return redirect('/login');
    }
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/resultados', [ResultadoController::class, 'create'])->name('resultados.create');
    Route::post('/resultados', [ResultadoController::class, 'store'])->name('resultados.store');
});


// Rutas Personalizadas para resultados



Route::get('/partidos', [PartidoController::class, 'index']);
Route::get('/partidos/create', [PartidoController::class, 'create']);
Route::post('/partidos', [PartidoController::class, 'store']);





Route::get('/mesas', [MesaController::class, 'index']);
Route::get('/mesas/create', [MesaController::class, 'create']);
Route::post('/mesas', [MesaController::class, 'store']);

Route::get('/candidatos/create', [CandidatoController::class, 'create']);
Route::post('/candidatos', [CandidatoController::class, 'store']);


Route::get('/cargos', [CargoController::class, 'index']);
Route::get('/cargos/create', [CargoController::class, 'create']);
Route::post('/cargos', [CargoController::class, 'store']);


Route::get('/departamentos', [DepartamentoController::class, 'index']);
Route::get('/departamentos/create', [DepartamentoController::class, 'create']);
Route::post('/departamentos', [DepartamentoController::class, 'store']);


Route::get('/provincias', [ProvinciaController::class, 'index']);
Route::get('/provincias/create', [ProvinciaController::class, 'create']);
Route::post('/provincias', [ProvinciaController::class, 'store']);



Route::get('/municipios', [MunicipioController::class, 'index']);
Route::get('/municipios/create', [MunicipioController::class, 'create']);
Route::post('/municipios', [MunicipioController::class, 'store']);


Route::get('/recintos', [RecintoController::class, 'index']);
Route::get('/recintos/create', [RecintoController::class, 'create']);
Route::post('/recintos', [RecintoController::class, 'store']);

Route::get('/delegado', [DelegadoController::class, 'index']);
Route::get('/delegado/create', [DelegadoController::class, 'create']);
Route::post('/delegado', [DelegadoController::class, 'store']);

Route::get('/consultas', [ConsultaController::class, 'index']);
Route::get('/consultas/consulta', [ConsultaController::class, 'consulta'])->name('consulta');
Route::post('/consultas', [ConsultaController::class, 'store']);


/* PROVINCIAS */

Route::get('/provincias/{id}', function ($id) {

    return DB::table('provincias')
        ->where('id_departamento', $id)
        ->get();
});


/* MUNICIPIOS */

Route::get('/municipios/{id}', function ($id) {

    return DB::table('municipios')
        ->where('id_provincia', $id)
        ->get();
});


/* LOCALIDADES */

Route::get('/localidades/{id}', function ($id) {

    return DB::table('localidades')
        ->where('id_municipio', $id)
        ->get();
});


/* RECINTOS */

Route::get('/recintos/{id}', function ($id) {

    return DB::table('recintos')
        ->where('id_localidad', $id)
        ->get();
});


/* RESULTADOS FILTRADOS */

Route::get('/resultados-filtrados', function () {

    $tipo = request('tipo_eleccion');

    $departamento = request('departamento');
    $provincia = request('provincia');
    $municipio = request('municipio');
    $localidad = request('localidad');
    $recinto = request('recinto');


    /* DEFINIR TIPO DE ELECCION */

    if ($tipo == 'alcaldia') {

        $cargo1 = 'Alcalde';
        $cargo2 = 'Concejal';
    } else {

        $cargo1 = 'Gobernador';
        $cargo2 = 'Asambleista';
    }


    /* BASE DE CONSULTA */

    $base = DB::table('votos_partido as vp')
        ->join('resultados as r', 'vp.id_resultado', '=', 'r.id_resultado')
        ->join('mesas as m', 'r.id_mesa', '=', 'm.id_mesa')
        ->join('recintos as re', 'm.id_recinto', '=', 're.id_recinto')
        ->join('localidades as lo', 're.id_localidad', '=', 'lo.id_localidad')
        ->join('municipios as mu', 'lo.id_municipio', '=', 'mu.id_municipio')
        ->join('provincias as pr', 'mu.id_provincia', '=', 'pr.id_provincia')
        ->join('departamentos as d', 'pr.id_departamento', '=', 'd.id_departamento')
        ->join('partido_cargo as pc', 'vp.id_partido_cargo', '=', 'pc.id')
        ->join('partidos as p', 'pc.id_partido', '=', 'p.id_partido')
        ->join('cargos as c', 'pc.id_cargo', '=', 'c.id_cargo');


    /* FILTROS TERRITORIALES */

    if ($departamento) {
        $base->where('d.id_departamento', $departamento);
    }

    if ($provincia) {
        $base->where('pr.id_provincia', $provincia);
    }

    if ($municipio) {
        $base->where('mu.id_municipio', $municipio);
    }

    if ($localidad) {
        $base->where('lo.id_localidad', $localidad);
    }

    if ($recinto) {
        $base->where('re.id_recinto', $recinto);
    }


    /* RESULTADO PRINCIPAL */

    $principal = (clone $base)
        ->where('c.nombre_cargo', $cargo1)
        ->select(
            'p.sigla',
            DB::raw('SUM(vp.votos) as votos')
        )
        ->groupBy('p.sigla')
        ->havingRaw('SUM(vp.votos) > 0')
        ->orderByDesc('votos')
        ->get();


    /* RESULTADO SECUNDARIO */

    $secundario = (clone $base)
        ->where('c.nombre_cargo', $cargo2)
        ->select(
            'p.sigla',
            DB::raw('SUM(vp.votos) as votos')
        )
        ->groupBy('p.sigla')
        ->havingRaw('SUM(vp.votos) > 0')
        ->orderByDesc('votos')
        ->get();

    /* RESULTADO TERCERO */

    $tercero = (clone $base)
        ->where('c.nombre_cargo', 'Asambleista Poblacion')
        ->select(
            'p.sigla',
            DB::raw('SUM(vp.votos) as votos')
        )
        ->groupBy('p.sigla')
        ->havingRaw('SUM(vp.votos) > 0')
        ->orderByDesc('votos')
        ->get();

    /* DETALLE */

    $detalle = DB::table('votos_especiales as ve')
        ->join('resultados as r', 've.id_resultado', '=', 'r.id_resultado')
        ->join('mesas as m', 'r.id_mesa', '=', 'm.id_mesa')
        ->join('recintos as re', 'm.id_recinto', '=', 're.id_recinto')
        ->join('localidades as lo', 're.id_localidad', '=', 'lo.id_localidad')
        ->join('municipios as mu', 'lo.id_municipio', '=', 'mu.id_municipio')
        ->join('provincias as pr', 'mu.id_provincia', '=', 'pr.id_provincia')
        ->join('departamentos as d', 'pr.id_departamento', '=', 'd.id_departamento')
        ->where('ve.tipo_eleccion', $tipo);


    if ($departamento) {
        $detalle->where('d.id_departamento', $departamento);
    }

    if ($provincia) {
        $detalle->where('pr.id_provincia', $provincia);
    }

    if ($municipio) {
        $detalle->where('mu.id_municipio', $municipio);
    }

    if ($localidad) {
        $detalle->where('lo.id_localidad', $localidad);
    }

    if ($recinto) {
        $detalle->where('re.id_recinto', $recinto);
    }


    $detalle = $detalle->select(

        DB::raw('SUM(ve.blancos) as blancos'),
        DB::raw('SUM(ve.nulos) as nulos'),
        DB::raw('SUM(ve.total_papeletas) as emitidos')

    )->first();


    return [

        'gobernador' => $principal,
        'asambleista' => $secundario,
        'asambleista_poblacion' => $tercero,
        'detalle' => $detalle

    ];
});
require __DIR__ . '/auth.php';
