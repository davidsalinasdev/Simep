@extends('layouts.app')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@php
use Illuminate\Support\Facades\DB;

$id_recinto = Auth::user()->id_recinto;

$ubicacion = DB::table('recintos as r')
->leftJoin('localidades as l','r.id_localidad','=','l.id_localidad')
->leftJoin('municipios as m','l.id_municipio','=','m.id_municipio')
->leftJoin('provincias as p','m.id_provincia','=','p.id_provincia')
->leftJoin('departamentos as d','p.id_departamento','=','d.id_departamento')
->select(
'r.nombre as recinto',
'l.nombre as localidad',
'm.nombre as municipio',
'm.id_municipio',
'p.nombre as provincia',
'd.nombre as departamento',
'd.id_departamento'
)
->where('r.id_recinto',$id_recinto)
->first();


if(Auth::user()->tipo_eleccion == 'Gobernador'){

$mesas = DB::table('mesas')
->where('id_recinto',$id_recinto)
->where('estado_gobernacion','pendiente')
->orderBy('numero_mesa')
->get();

}else{

$mesas = DB::table('mesas')
->where('id_recinto',$id_recinto)
->where('estado_alcaldia','pendiente')
->orderBy('numero_mesa')
->get();

}



/* ============================
PARTIDOS SEGUN ELECCION
============================ */

if(Auth::user()->tipo_eleccion == 'Gobernador'){

$gobernador = DB::table('partido_cargo as pc')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Gobernador')
->where('pc.id_departamento',$ubicacion->id_departamento)
->select('pc.id as id_partido_cargo','p.nombre','p.sigla')
->get();


$asambleista = DB::table('partido_cargo as pc')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Asambleista')
->where('pc.id_departamento',$ubicacion->id_departamento)
->select('pc.id as id_partido_cargo','p.nombre','p.sigla')
->get();

$asambleista_poblacion = DB::table('partido_cargo as pc')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Asambleista Poblacion')
->where('pc.id_departamento',$ubicacion->id_departamento)
->select('pc.id as id_partido_cargo','p.nombre','p.sigla')
->get();

}else{

$alcalde = DB::table('partido_cargo as pc')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->where('pc.id_cargo',2)
->where('pc.id_municipio',$ubicacion->id_municipio)
->select('pc.id as id_partido_cargo','p.nombre','p.sigla')
->get();


$concejal = DB::table('partido_cargo as pc')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->where('pc.id_cargo',4)
->where('pc.id_municipio',$ubicacion->id_municipio)
->select('pc.id as id_partido_cargo','p.nombre','p.sigla')
->get();

}

@endphp

<style>
    .is-invalid {
        border: 2px solid red !important;
        background-color: #ffe6e6;
    }
</style>

<div class="card shadow-lg">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Registro de Resultados del departamento de {{ $ubicacion->departamento }} - SIMEP - {{ Auth::user()->tipo_eleccion }}
        </h5>

        <div class="dropdown">

            <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                {{ Auth::user()->email }}
            </button>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item">
                            Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="card-body">

        <form id="formResultados" method="POST" action="{{ route('resultados.store') }}" enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="tipo_eleccion"
                value="{{ Auth::user()->tipo_eleccion == 'Gobernador' ? 'gobernacion' : 'alcaldia' }}">

            <div class="row mb-4">

                <div class="col-md-4">

                    <label class="fw-bold">Mesa</label>

                    <select id="mesaSelect" name="id_mesa" class="form-select" required>

                        <option value="">-Seleccione mesa-</option>

                        @foreach($mesas as $mesa)

                        <option value="{{ $mesa->id_mesa }}">
                            Mesa {{ $mesa->numero_mesa }}
                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">
                    <label class="fw-bold">Provincia</label>
                    <p>{{ $ubicacion->provincia }}</p>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold">Municipio</label>
                    <p>{{ $ubicacion->municipio }}</p>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold">Localidad</label>
                    <p>{{ $ubicacion->localidad }}</p>
                </div>

                <div class="col-md-2">
                    <label class="fw-bold">Recinto</label>
                    <p>{{ $ubicacion->recinto }}</p>
                </div>

            </div>



            <div id="mesaTitulo" class="text-center mb-4" style="display:none">

                <h1 class="text-danger">
                    NRO. MESA: <span id="numeroMesa"></span>
                </h1>

            </div>



            <div id="formularioResultados" style="display:none">



                <div class="accordion" id="accordionResultados">

                    {{-- ======================
    GOBERNADOR
    ====================== --}}
                    @if(Auth::user()->tipo_eleccion == 'Gobernador')

                    <!-- ================= GOBERNADOR ================= -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#gobernadorCollapse">
                                Votos Gobernador
                            </button>
                        </h2>

                        <div id="gobernadorCollapse" class="accordion-collapse collapse show" data-bs-parent="#accordionResultados">
                            <div class="accordion-body">

                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Partido</th>
                                            <th>Votos Gobernador</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gobernador as $partido)
                                        <tr>
                                            <td>
                                                <strong>{{ $partido->sigla }}</strong><br>
                                                <small>{{ $partido->nombre }}</small>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                                    class="form-control votos sumar gobernador"
                                                    value="0" min="0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card p-3 border-primary">
                                    <h6 class="text-primary">Gobernador</h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Blancos</label>
                                            <input type="number" name="especial[gobernador][blancos]" class="form-control sumar gobernador" value="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nulos</label>
                                            <input type="number" name="especial[gobernador][nulos]" class="form-control sumar gobernador" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label>Total Gobernador</label>
                                        <input type="number" id="total_gobernador" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ================= ASAMBLEÍSTA TERRITORIO ================= -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#territorioCollapse">
                                Votos Asambleísta por Territorio
                            </button>
                        </h2>

                        <div id="territorioCollapse" class="accordion-collapse collapse" data-bs-parent="#accordionResultados">
                            <div class="accordion-body">

                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Partido</th>
                                            <th>Votos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asambleista as $partido)
                                        <tr>
                                            <td>
                                                <strong>{{ $partido->sigla }}</strong><br>
                                                <small>{{ $partido->nombre }}</small>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                                    class="form-control votos sumar asambleista"
                                                    value="0" min="0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card p-3 border-primary">
                                    <h6 class="text-primary">Asambleísta Territorio</h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Blancos</label>
                                            <input type="number" name="especial[asambleista][blancos]" class="form-control sumar asambleista" value="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nulos</label>
                                            <input type="number" name="especial[asambleista][nulos]" class="form-control sumar asambleista" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label>Total</label>
                                        <input type="number" id="total_asambleista" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ================= ASAMBLEÍSTA POBLACIÓN ================= -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#poblacionCollapse">
                                Votos Asambleísta por Población
                            </button>
                        </h2>

                        <div id="poblacionCollapse" class="accordion-collapse collapse" data-bs-parent="#accordionResultados">
                            <div class="accordion-body">

                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Partido</th>
                                            <th>Votos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($asambleista_poblacion as $partido)
                                        <tr>
                                            <td>
                                                <strong>{{ $partido->sigla }}</strong><br>
                                                <small>{{ $partido->nombre }}</small>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                                    class="form-control votos sumar asambleista_poblacion"
                                                    value="0" min="0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card p-3 border-primary">
                                    <h6 class="text-primary">Asambleísta Población</h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Blancos</label>
                                            <input type="number" name="especial[asambleista_poblacion][blancos]" class="form-control sumar asambleista_poblacion" value="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nulos</label>
                                            <input type="number" name="especial[asambleista_poblacion][nulos]" class="form-control sumar asambleista_poblacion" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label>Total</label>
                                        <input type="number" id="total_asambleista_poblacion" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endif

                </div>


                {{-- ======================
ALCALDE
====================== --}}
                <div class="accordion" id="accordionResultadosAlcaldia">

                    @if(Auth::user()->tipo_eleccion == 'Alcalde')

                    <!-- ================= ALCALDE ================= -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#alcaldeCollapse">
                                Votos Alcalde
                            </button>
                        </h2>

                        <div id="alcaldeCollapse" class="accordion-collapse collapse show" data-bs-parent="#accordionResultadosAlcaldia">
                            <div class="accordion-body">

                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Partido</th>
                                            <th>Votos Alcalde</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alcalde as $partido)
                                        <tr>
                                            <td>
                                                <strong>{{ $partido->sigla }}</strong><br>
                                                <small>{{ $partido->nombre }}</small>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                                    class="form-control votos sumar alcalde"
                                                    value="0" min="0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card p-3 border-success">
                                    <h6 class="text-success">Alcalde</h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Blancos</label>
                                            <input type="number" name="especial[alcalde][blancos]" class="form-control sumar alcalde" value="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nulos</label>
                                            <input type="number" name="especial[alcalde][nulos]" class="form-control sumar alcalde" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label>Total Alcalde</label>
                                        <input type="number" id="total_alcalde" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ================= CONCEJAL ================= -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#concejalCollapse">
                                Votos Concejal
                            </button>
                        </h2>

                        <div id="concejalCollapse" class="accordion-collapse collapse" data-bs-parent="#accordionResultadosAlcaldia">
                            <div class="accordion-body">

                                <table class="table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Partido</th>
                                            <th>Votos Concejal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($concejal as $partido)
                                        <tr>
                                            <td>
                                                <strong>{{ $partido->sigla }}</strong><br>
                                                <small>{{ $partido->nombre }}</small>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                                    class="form-control votos sumar concejal"
                                                    value="0" min="0">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="card p-3 border-success">
                                    <h6 class="text-success">Concejal</h6>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Blancos</label>
                                            <input type="number" name="especial[concejal][blancos]" class="form-control sumar concejal" value="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nulos</label>
                                            <input type="number" name="especial[concejal][nulos]" class="form-control sumar concejal" value="0">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label>Total Concejal</label>
                                        <input type="number" id="total_concejal" class="form-control" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endif

                </div>



                {{-- TOTAL GENERAL --}}
                <!-- <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Total general de votos</label>
                        <input type="number"
                            name="total_papeletas"
                            id="total_papeletas"
                            class="form-control"

                            readonly>
                    </div>
                </div> -->


                <hr>

                <label>Foto del Acta</label>

                <input
                    type="file"
                    name="imagen_acta"
                    id="imagen_acta"
                    class="form-control"
                    accept="image/*"
                    capture="environment">


                <hr>

                <button type="button" id="btnGuardar" class="btn btn-success w-100">
                    Guardar Resultados
                </button>

            </div>

        </form>

    </div>

</div>

@if(session('success'))

<script>
    Swal.fire({
        icon: 'success',
        title: 'Correcto',
        text: 'success ',
        confirmButtonColor: '#28a745'
    });
</script>

@endif


@if(session('error'))

<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'error'
    });
</script>

@endif


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const mesaSelect = document.getElementById("mesaSelect");

        mesaSelect.addEventListener("change", function() {

            // 🔥 LIMPIAR TODO AL CAMBIAR DE MESA
            limpiarFormulario();

            const texto = this.options[this.selectedIndex].text;

            if (this.value != "") {

                document.getElementById("formularioResultados").style.display = "block";
                document.getElementById("mesaTitulo").style.display = "block";

                document.getElementById("numeroMesa").innerText =
                    texto.replace("Mesa ", "");

            } else {

                document.getElementById("formularioResultados").style.display = "none";
                document.getElementById("mesaTitulo").style.display = "none";

            }

        });

        document.getElementById("btnGuardar").addEventListener("click", function() {

            let valido = true;

            document.querySelectorAll("input[type='number']").forEach(input => {

                if (input.value === "" || input.value < 0) {
                    valido = false;
                    input.classList.add("is-invalid");
                } else {
                    input.classList.remove("is-invalid");
                }

            });

            if (!valido) {
                Swal.fire({
                    icon: "error",
                    title: "Error en los datos",
                    text: "Verifique que no existan campos vacíos o valores negativos"
                });
                return;
            }

            // CONFIRMACIÓN
            Swal.fire({
                title: "¿Guardar resultados?",
                text: "Verifique que los datos sean correctos",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, guardar",
                cancelButtonText: "Cancelar"
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: "Guardando resultados...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    document.querySelector("#formResultados").submit();
                }

            });

        });


        function calcularTotalesPorCargo() {

            function sumar(clase) {
                let total = 0;
                document.querySelectorAll("." + clase).forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                return total;
            }

            if (document.getElementById("total_gobernador")) {
                document.getElementById("total_gobernador").value = sumar("gobernador");
            }

            if (document.getElementById("total_asambleista")) {
                document.getElementById("total_asambleista").value = sumar("asambleista");
            }

            if (document.getElementById("total_asambleista_poblacion")) {
                document.getElementById("total_asambleista_poblacion").value = sumar("asambleista_poblacion");
            }

            if (document.getElementById("total_alcalde")) {
                document.getElementById("total_alcalde").value = sumar("alcalde");
            }

            if (document.getElementById("total_concejal")) {
                document.getElementById("total_concejal").value = sumar("concejal");
            }
        }

        document.querySelectorAll(".votos, .sumar").forEach(function(input) {
            input.addEventListener("input", function() {

                calcularTotalesPorCargo();
            });
        });


        const inputs = document.querySelectorAll("input[type='number']");

        inputs.forEach(input => {

            input.addEventListener("input", function() {

                // 🚫 No permitir negativos
                if (this.value < 0) {
                    this.value = 0;

                    Swal.fire({
                        icon: "warning",
                        title: "Valor inválido",
                        text: "No se permiten números negativos"
                    });
                }
                // 🚫 Quitar ceros a la izquierda 🔥
                if (this.value.length > 1 && this.value.startsWith("0")) {
                    this.value = this.value.replace(/^0+/, "");
                }

            });

            // ✅ SOLO cuando el usuario SALE del campo
            input.addEventListener("blur", function() {
                if (this.value === "") {
                    this.value = 0;
                }
            });

        });

        function limpiarFormulario() {

            // 🔹 Todos los inputs numéricos a 0
            document.querySelectorAll("input[type='number']").forEach(input => {
                input.value = 0;
                input.classList.remove("is-invalid");
            });

            // 🔹 Limpiar archivo
            const fileInput = document.getElementById("imagen_acta");
            if (fileInput) fileInput.value = "";

            // 🔹 Resetear totales
            const totales = [
                "total_gobernador",
                "total_asambleista",
                "total_asambleista_poblacion",
                "total_alcalde",
                "total_concejal",
                "total_papeletas"
            ];

            totales.forEach(id => {
                let el = document.getElementById(id);
                if (el) el.value = 0;
            });
        }

    });
</script>