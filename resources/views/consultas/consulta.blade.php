@extends('layouts.guest')

<div class="container-fluid mt-8">
    <div class="d-flex justify-content-between align-items-center">

        <h4 class="mb-3">Resultados Preliminares - SIMEP</h4>

        <div>
            <a href="{{ route('consulta') }}" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt"></i> Actualizar
            </a>
        </div>

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


    <!-- FILTROS -->

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-2">

                    <label class="form-label fw-bold">Tipo Elección</label>

                    <select class="form-select" id="tipo_eleccion">
                        <option>Gobernador</option>
                        <option>Alcalde</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">Departamento</label>

                    <select class="form-select" id="departamento">
                        <option>-seleccionar-</option>
                        <option selected>Cochabamba</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">Provincia</label>

                    <select class="form-select" id="provincia">
                        <option selected>-seleccionar-</option>
                        <option>Cercado</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">Municipio</label>

                    <select class="form-select" id="municipio">
                        <option selected>-seleccionar-</option>
                        <option>Cochabamba</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">Localidad</label>

                    <select class="form-select" id="localidad">
                        <option selected>-seleccionar-</option>
                        <option>Centro</option>
                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label fw-bold">Recinto</label>

                    <select class="form-select" id="recinto">
                        <option selected>-seleccionar-</option>
                        <option>Unidad Educativa Bolívar</option>
                    </select>

                </div>

            </div>

        </div>

    </div>

    <!-- RESULTADOS -->

    <div class="row">

        <!-- GRAFICO -->

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-header fw-bold">

                    Resultados Gráficos

                </div>

                <div class="card-body">

                    <canvas id="graficoResultados"></canvas>

                </div>

            </div>

        </div>

        <!-- PANEL DE DETALLE -->

        <div class="col-lg-4">

            <div class="card shadow">

                <div class="card-header bg-danger text-white fw-bold">

                    Detalle

                </div>

                <div class="card-body p-0">

                    <table class="table mb-0">

                        <thead class="table-danger">

                            <tr>

                                <th>Detalle</th>
                                <th>Total</th>
                                <th>%</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>Votos Válidos</td>
                                <td>1,190</td>
                                <td>95.6%</td>

                            </tr>

                            <tr>

                                <td>Votos Blancos</td>
                                <td>25</td>
                                <td>2.0%</td>

                            </tr>

                            <tr>

                                <td>Votos Nulos</td>
                                <td>30</td>
                                <td>2.4%</td>

                            </tr>

                            <tr>

                                <td>Votos Emitidos</td>
                                <td>1,245</td>
                                <td>-</td>

                            </tr>

                        </tbody>

                    </table>

                    <div class="p-3 text-muted small">

                        Fecha del servidor:<br>

                        {{ now() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('graficoResultados');

    new Chart(ctx, {

        type: 'bar',

        data: {

            labels: [

                'MAS - IPSP',

                'Comunidad Ciudadana',

                'Creemos',

                'Libre'

            ],

            datasets: [{

                label: 'Votos',

                data: [

                    650,

                    320,

                    180,

                    40

                ],

                backgroundColor: [

                    '#0056b3',

                    '#ff5733',

                    '#2ecc71',

                    '#f1c40f'

                ]

            }]

        },

        options: {

            indexAxis: 'y',

            plugins: {

                legend: {

                    display: false

                }

            }

        }

    });
    document.getElementById("tipo_eleccion").addEventListener("change", function() {

        document.getElementById("departamento").selectedIndex = 0;
        document.getElementById("provincia").selectedIndex = 0;
        document.getElementById("municipio").selectedIndex = 0;
        document.getElementById("localidad").selectedIndex = 0;
        document.getElementById("recinto").selectedIndex = 0;

    });
</script>