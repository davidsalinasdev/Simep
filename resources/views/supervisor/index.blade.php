@extends('layouts.app')

<div class="container">

    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Editar Mesa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="contenido_modal">
                    <!-- aquí cargaremos el form -->
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3>Corrección de Mesas</h3>

        <!-- 🔄 Botón actualizar -->


        <!-- 🔴 Cerrar sesión -->
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="button" onclick="recargarPagina()" class="btn btn-primary">
                Actualizar
            </button>
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
    function recargarPagina() {
        location.reload();
    }
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

    // 🔥 FUNCIÓN GLOBAL
    function activarSumas() {

        function calcularTotales() {

            let modal = document.getElementById('modalEditar');

            modal.querySelectorAll('[id^="total_"]').forEach(totalInput => {

                let clase = totalInput.id.replace('total_', '');
                let total = 0;

                modal.querySelectorAll('.' + clase).forEach(i => {
                    total += parseInt(i.value) || 0;
                });

                totalInput.value = total;
            });
        }

        document.querySelectorAll('#modalEditar .sumar').forEach(i => {
            i.addEventListener('input', calcularTotales);
        });

        calcularTotales();
    }

    function abrirModal(id) {
        console.log("Mesa clickeada:", id);
        fetch(`/supervisor/editar/${id}`)
            .then(r => r.text())
            .then(html => {

                document.getElementById('contenido_modal').innerHTML = html;

                // ✅ CORRECTO
                let modal = new bootstrap.Modal(document.getElementById('modalEditar'));

                modal.show();
                // 🔥 SOLUCIÓN
                activarSumas();
            });

    }

    // 🔥 VALIDACIÓN GLOBAL (PRO)
    document.addEventListener('input', function(e) {

        if (e.target.classList.contains('sumar')) {

            let val = e.target.value;

            // quitar negativos
            val = val.replace('-', '');

            // solo números
            val = val.replace(/[^0-9]/g, '');

            // quitar ceros a la izquierda
            val = val.replace(/^0+(\d)/, '$1');

            // 👉 SI QUEDA VACÍO → poner 0
            if (val === '') {
                val = '0';
            }

            e.target.value = val;
        }

    });
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
                            <button class="btn btn-warning"
                                onclick="abrirModal(${m.id_mesa})">
                                Editar
                            </button>
                        </td>
                    </tr>
                `;
                });

                html += '</tbody></table>';

                document.getElementById('mesas_container').innerHTML = html;

            });

    });


    // Enviar formulario editar mesa
    document.addEventListener('submit', function(e) {

        if (e.target.id === 'formEditar') {
            e.preventDefault();

            let formData = new FormData(e.target);

            fetch('/supervisor/actualizar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(r => r.text())
                .then(() => {
                    Swal.fire('Correcto', 'Datos actualizados', 'success');

                    // cerrar modal
                    let modal = bootstrap.Modal.getInstance(document.getElementById('modalEditar'));
                    modal.hide();

                    // 🔥 OPCIÓN 1: recargar todo
                    // location.reload();

                    // 🔥 OPCIÓN 2 (pro): volver a cargar mesas sin reload
                    // document.getElementById('recinto').dispatchEvent(new Event('change'));
                    // 🔥 👉 AQUÍ VA
                    document.getElementById('recinto').dispatchEvent(new Event('change'));
                });
        }

    });
</script>