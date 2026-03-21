@extends('layouts.app')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Corrección de Mesas</h3>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>

    <!-- FILTROS -->
    <div class="row mb-4">

        <div class="col-md-2">
            <label>Tipo Elección</label>
            <select id="tipo" class="form-select">
                <option value="gobernacion">Gobernador</option>
                <option value="alcaldia">Alcalde</option>
            </select>
        </div>

        <div class="col-md-2">
            <label>Departamento</label>
            <select id="departamento" class="form-select"></select>
        </div>

        <div class="col-md-2">
            <label>Provincia</label>
            <select id="provincia" class="form-select"></select>
        </div>

        <div class="col-md-2">
            <label>Municipio</label>
            <select id="municipio" class="form-select"></select>
        </div>

        <div class="col-md-2">
            <label>Localidad</label>
            <select id="localidad" class="form-select"></select>
        </div>

        <div class="col-md-2">
            <label>Recinto</label>
            <select id="recinto" class="form-select"></select>
        </div>

    </div>

    <!-- TABLA MESAS -->
    <div id="mesas_container"></div>

</div>
<script>
    // 🔥 FUNCIÓN GENERAL
    function cargar(url, select, idField, nameField) {
        fetch(url)
            .then(r => r.json())
            .then(data => {
                select.innerHTML = '<option value="">-seleccionar-</option>';
                data.forEach(d => {
                    select.innerHTML += `<option value="${d[idField]}">${d[nameField]}</option>`;
                });
            });
    }

    // 🔥 CARGAR DEPARTAMENTOS AL INICIO
    document.addEventListener('DOMContentLoaded', function() {
        cargar('/departamentos', document.getElementById('departamento'), 'id_departamento', 'nombre');
    });


    // 🔥 CASCADAS

    document.getElementById('departamento').addEventListener('change', function() {
        cargar('/provincias/' + this.value, document.getElementById('provincia'), 'id_provincia', 'nombre');
        document.getElementById('municipio').innerHTML = '';
        document.getElementById('localidad').innerHTML = '';
        document.getElementById('recinto').innerHTML = '';
        document.getElementById('mesas_container').innerHTML = '';
    });

    document.getElementById('provincia').addEventListener('change', function() {
        cargar('/municipios/' + this.value, document.getElementById('municipio'), 'id_municipio', 'nombre');
    });

    document.getElementById('municipio').addEventListener('change', function() {
        cargar('/localidades/' + this.value, document.getElementById('localidad'), 'id_localidad', 'nombre');
    });

    document.getElementById('localidad').addEventListener('change', function() {
        cargar('/recintos/' + this.value, document.getElementById('recinto'), 'id_recinto', 'nombre');
    });


    // 🔥 RECINTO → MESAS
    document.getElementById('recinto').addEventListener('change', function() {

        let recinto = this.value;

        let tipo = document.getElementById('tipo').value;

        fetch(`/mesas-por-recinto/${recinto}?tipo=${tipo}`)
            .then(r => r.json())
            .then(data => {

                let html = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mesa</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

                data.forEach(m => {
                    html += `
                    <tr>
                        <td>Mesa ${m.numero_mesa}</td>
                        <td>
                            <a href="/supervisor/editar/${m.id_mesa}" class="btn btn-warning">
                                Editar
                            </a>
                        </td>
                    </tr>
                `;
                });

                html += '</tbody></table>';

                document.getElementById('mesas_container').innerHTML = html;

            });

    });
</script>