@extends('layouts.guest')
<div class="card shadow-lg">

    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Registro de Resultados - SIMEP
        </h5>

        <div class="dropdown">

            <button class="btn btn-dark dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                David Salinas

            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li class="dropdown-item-text text-muted">
                    Usuario conectado
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="dropdown-item text-danger">
                            Cerrar sesión
                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

    <div class="card-body">

        <form method="POST" action="/resultados" enctype="multipart/form-data">
            @csrf

            <div class="row mb-4 align-items-end">

                <div class="col-md-3">

                    <label class="fw-bold">Mesa</label>

                    <select id="mesaSelect" name="id_mesa" class="form-select" required>

                        <option value="">-Seleccione mesa-</option>

                        <option value="1">
                            Mesa 1
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="fw-bold">Provincia</label>
                    <p>Cercado</p>

                </div>

                <div class="col-md-3">

                    <label class="fw-bold">Municipio</label>
                    <p>Cochabamba</p>

                </div>

                <div class="col-md-3">

                    <label class="fw-bold">Recinto</label>
                    <p>Sergio Almaraz Paz</p>

                </div>

            </div>

            <!-- NÚMERO DE MESA GRANDE -->

            <div id="mesaTitulo" class="text-center mb-4" style="display:none;">

                <h1 class="text-danger fw-bold">
                    NRO. MESA: <span id="numeroMesa"></span>
                </h1>

            </div>

            <!-- FORMULARIO OCULTO -->

            <div id="formularioResultados" style="display:none;">

                <hr>

                <h5 class="mb-3 text-primary">Votos por Partido</h5>

                <table class="table table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Partido</th>
                            <th width="200">Votos</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($partidos as $partido)

                        <tr>

                            <td>

                                <strong>{{ $partido->sigla }}</strong>
                                <br>
                                <small>{{ $partido->nombre }}</small>

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="votos[{{ $partido->id_partido }}]"
                                    class="form-control votos"
                                    min="0"
                                    value="0"
                                    required>

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

                <hr>

                <h5 class="text-primary">Votos Especiales</h5>

                <div class="row">

                    <div class="col-lg-4">

                        <label class="fw-bold">Blancos</label>

                        <input type="number"
                            name="blancos"
                            class="form-control votos"
                            value="0">

                    </div>

                    <div class="col-lg-4">

                        <label class="fw-bold">Nulos</label>

                        <input type="number"
                            name="nulos"
                            class="form-control votos"
                            value="0">

                    </div>

                    <div class="col-lg-4">

                        <label class="fw-bold">Total papeletas</label>

                        <input type="number"
                            name="total_papeletas"
                            class="form-control"
                            required>

                    </div>

                </div>

                <hr>

                <h5 class="text-primary">Foto del Acta</h5>

                <input
                    type="file"
                    name="imagen_acta"
                    class="form-control"
                    accept="image/*"
                    capture="environment"
                    required>

                <hr>

                <button class="btn btn-success btn-lg w-100">

                    Guardar Resultados

                </button>

            </div>

        </form>

    </div>

</div>

<script>
    document.getElementById("mesaSelect").addEventListener("change", function() {

        let texto = this.options[this.selectedIndex].text;

        if (this.value !== "") {

            document.getElementById("formularioResultados").style.display = "block";
            document.getElementById("mesaTitulo").style.display = "block";

            document.getElementById("numeroMesa").innerText = texto.replace("Mesa ", "");

        }

    });
</script>