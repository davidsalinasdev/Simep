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
    } elseif ($rol == 'supervisor') { // 🔥 NUEVO
        return redirect('/supervisor');
    } else {

        return redirect('/login');
    }
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


/* =========================
   🔥 ROL: DELEGADO RECINTO
========================= */
Route::middleware(['auth', 'rol:delegado_recinto'])->group(function () {

    Route::get('/delegado', [DelegadoController::class, 'index']);
    Route::get('/delegado/create', [DelegadoController::class, 'create']);
    Route::post('/delegado', [DelegadoController::class, 'store']);

    Route::get('/resultados', [ResultadoController::class, 'create'])->name('resultados.create');
    Route::post('/resultados', [ResultadoController::class, 'store'])->name('resultados.store'); // 🔥 AQUI
});



/* =========================
   🔥 ROL: CONSULTA
========================= */
Route::middleware(['auth', 'rol:consulta'])->group(function () {

    Route::get('/consultas', [ConsultaController::class, 'index']);
    Route::get('/consultas/consulta', [ConsultaController::class, 'consulta'])->name('consulta');
    Route::post('/consultas', [ConsultaController::class, 'store']);
});


/* =========================
   🔥 ROL: SUPERVISOR
========================= */
Route::middleware(['auth', 'rol:supervisor'])->group(function () {

    Route::get('/supervisor', [ResultadoController::class, 'supervisor']);
    Route::get('/supervisor/editar/{id}', [ResultadoController::class, 'editarMesa']);
    Route::post('/supervisor/actualizar', [ResultadoController::class, 'actualizar']);
});


/* =========================
   🔥 ROL: (otros si quieres)
========================= */
// Ejemplo:
// Route::middleware(['auth', 'rol:delegado_recinto'])->group(function () {
//     Route::get('/delegado/create', [DelegadoController::class, 'create']);
// });


/* =========================
   🔥 RESULTADOS (puedes dejar libre o proteger)
========================= */
// Route::middleware(['auth'])->group(function () {

//     Route::get('/resultados', [ResultadoController::class, 'create'])->name('resultados.create');
//     Route::post('/resultados', [ResultadoController::class, 'store']);
// });



Route::get('/departamentos', function () {
    return DB::table('departamentos')->orderBy('nombre')->get();
});

Route::get('/mesas-por-recinto/{id}', function ($id) {

    $tipo = request('tipo'); // 🔥 recibimos tipo

    $query = DB::table('mesas')
        ->where('id_recinto', $id);

    if ($tipo == 'gobernacion') {
        $query->where('estado_gobernacion', 'enviado');
    } else {
        $query->where('estado_alcaldia', 'enviado');
    }

    return $query->orderBy('numero_mesa')->get();
});

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



    function obtenerDetalle($baseDetalle, $cargoNombre)
    {
        return (clone $baseDetalle)
            ->join('cargos as c', 've.id_cargo', '=', 'c.id_cargo')
            ->where('c.nombre_cargo', $cargoNombre)
            ->select(
                DB::raw('SUM(ve.blancos) as blancos'),
                DB::raw('SUM(ve.nulos) as nulos')
            )
            ->first();
    }

    $baseDetalle = DB::table('votos_especiales as ve')
        ->join('resultados as r', 've.id_resultado', '=', 'r.id_resultado')
        ->join('mesas as m', 'r.id_mesa', '=', 'm.id_mesa')
        ->join('recintos as re', 'm.id_recinto', '=', 're.id_recinto')
        ->join('localidades as lo', 're.id_localidad', '=', 'lo.id_localidad')
        ->join('municipios as mu', 'lo.id_municipio', '=', 'mu.id_municipio')
        ->join('provincias as pr', 'mu.id_provincia', '=', 'pr.id_provincia')
        ->join('departamentos as d', 'pr.id_departamento', '=', 'd.id_departamento')
        ->where('ve.tipo_eleccion', $tipo);

    /* FILTROS */
    if ($departamento) $baseDetalle->where('d.id_departamento', $departamento);
    if ($provincia) $baseDetalle->where('pr.id_provincia', $provincia);
    if ($municipio) $baseDetalle->where('mu.id_municipio', $municipio);
    if ($localidad) $baseDetalle->where('lo.id_localidad', $localidad);
    if ($recinto) $baseDetalle->where('re.id_recinto', $recinto);

    /* DETALLES */
    $detalleGob = obtenerDetalle($baseDetalle, 'Gobernador');
    $detalleAsam = obtenerDetalle($baseDetalle, 'Asambleista');
    $detalleAsamPob = obtenerDetalle($baseDetalle, 'Asambleista Poblacion');
    $detalleAlcalde = obtenerDetalle($baseDetalle, 'Alcalde');
    $detalleConcejal = obtenerDetalle($baseDetalle, 'Concejal');





    return [

        // 🔥 ESTO FALTABA
        'gobernador' => $principal,
        'asambleista' => $secundario,
        'asambleista_poblacion' => $tercero,

        // 🔥 TUS NUEVOS DETALLES
        'detalle_gob' => $detalleGob,
        'detalle_asam' => $detalleAsam,
        'detalle_asam_pob' => $detalleAsamPob,
        'detalle_alcalde' => $detalleAlcalde,
        'detalle_concejal' => $detalleConcejal

    ];
});
require __DIR__ . '/auth.php';
