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


$mesas = DB::table('mesas')
->where('id_recinto',$id_recinto)
->where('estado','pendiente')
->orderBy('numero_mesa')
->get();



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



<div class="card shadow-lg">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Registro de Resultados del departamento de {{ $ubicacion->departamento }} - SIMEP - {{ Auth::user()->tipo_eleccion }}
        </h5>

        <div class="dropdown">

            <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                {{ Auth::user()->nombre }}
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

                <div class="col-md-3">

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



                <div class="col-md-3">
                    <label class="fw-bold">Provincia</label>
                    <p>{{ $ubicacion->provincia }}</p>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">Municipio</label>
                    <p>{{ $ubicacion->municipio }}</p>
                </div>

                <div class="col-md-3">
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



                {{-- ======================
GOBERNADOR
====================== --}}

                @if(Auth::user()->tipo_eleccion == 'Gobernador')

                <h5 class="text-primary">Votos Gobernador</h5>

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
                                <strong>{{ $partido->sigla }}</strong>
                                <br>
                                <small>{{ $partido->nombre }}</small>
                            </td>

                            <td>
                                <input type="number"
                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                    class="form-control votos sumar"
                                    value="0"
                                    min="0">
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>



                <h5 class="text-primary">Votos Asambleísta</h5>

                <table class="table">

                    <thead class="table-dark">
                        <tr>
                            <th>Partido</th>
                            <th>Votos Asambleísta</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($asambleista as $partido)

                        <tr>

                            <td>
                                <strong>{{ $partido->sigla }}</strong>
                                <br>
                                <small>{{ $partido->nombre }}</small>
                            </td>

                            <td>
                                <input type="number"
                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                    class="form-control votos"
                                    value="0"
                                    min="0">
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                @endif



                {{-- ======================
ALCALDE
====================== --}}

                @if(Auth::user()->tipo_eleccion == 'Alcalde')

                <h5 class="text-primary">Votos Alcalde</h5>

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
                                <strong>{{ $partido->sigla }}</strong>
                                <br>
                                <small>{{ $partido->nombre }}</small>
                            </td>

                            <td>
                                <input type="number"
                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                    class="form-control votos sumar"
                                    value="0"
                                    min="0">
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>



                <h5 class="text-primary">Votos Concejal</h5>

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
                                <strong>{{ $partido->sigla }}</strong>
                                <br>
                                <small>{{ $partido->nombre }}</small>
                            </td>

                            <td>
                                <input type="number"
                                    name="votos[{{ $partido->id_partido_cargo }}]"
                                    class="form-control votos"
                                    value="0"
                                    min="0">
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                @endif



                <h5 class="text-primary">Votos Especiales</h5>

                <div class="row">

                    <div class="col-md-4">
                        <label>Blancos</label>
                        <input type="number" name="blancos" class="form-control votos sumar" value="0">
                    </div>

                    <div class="col-md-4">
                        <label>Nulos</label>
                        <input type="number" name="nulos" class="form-control votos sumar" value="0">
                    </div>

                    <div class="col-md-4">
                        <label>Total papeletas</label>
                        <input type="number"
                            name="total_papeletas"
                            id="total_papeletas"
                            class="form-control"
                            readonly>
                    </div>

                </div>


                <hr>

                <label>Foto del Acta</label>

                <input
                    type="file"
                    name="imagen_acta"
                    id="imagen_acta"
                    class="form-control"
                    accept="image/*"
                    capture="environment"
                    required>


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

            const imagen = document.querySelector("input[name='imagen_acta']").value;

            if (imagen == "") {

                Swal.fire({
                    icon: "error",
                    title: "Foto obligatoria",
                    text: "Debe subir la foto del acta antes de guardar"
                });

                return;

            }

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
                        text: "Espere por favor",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.querySelector("#formResultados").submit();

                }

            });

        });



        function calcularTotal() {

            let total = 0;

            document.querySelectorAll(".sumar").forEach(function(input) {

                total += parseInt(input.value) || 0;

            });

            document.getElementById("total_papeletas").value = total;

        }

        document.querySelectorAll(".sumar").forEach(function(input) {

            input.addEventListener("input", calcularTotal);

        });


    });
</script>