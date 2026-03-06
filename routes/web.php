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
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rutas Personalizadas para resultados

Route::get('/resultados/{id_mesa}', [ResultadoController::class, 'create']);
Route::post('/resultados', [ResultadoController::class, 'store']);


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');


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
Route::get('/consultas/consulta', [ConsultaController::class, 'consulta']);
Route::post('/consultas', [ConsultaController::class, 'store']);


require __DIR__ . '/auth.php';
