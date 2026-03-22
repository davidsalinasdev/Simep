@extends('layouts.app')
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border-radius: 8px;
        padding: 5px;
    }

    .select2-selection__rendered {
        line-height: 26px !important;
    }

    .select2-selection__arrow {
        height: 38px !important;
    }
</style>
<div class="container">

    <div class="modal fade" id="modalEditar" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Editar Mesas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="contenido_modal">
                    <!-- aquí cargaremos el form -->
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h2 class="fw-bold">🗳️ Corrección de Mesasss</h2>

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
    function activarSelect2() {

        $('#municipio').select2({
            placeholder: "Buscar municipio...",
            width: '100%'
        });

        $('#localidad').select2({
            placeholder: "Buscar localidad...",
            width: '100%'
        });

        $('#recinto').select2({
            placeholder: "Buscar recinto...",
            width: '100%'
        });

    }
    // 🔥 FUNCIÓN GENERAL
    function cargar(url, select, idField, nameField) {

        fetch(url)
            .then(r => r.json())
            .then(data => {

                // 🔥 destruir select2 si existe
                if ($(select).hasClass("select2-hidden-accessible")) {
                    $(select).select2('destroy');
                }

                select.innerHTML = '<option value="">-seleccionar-</option>';

                data.forEach(d => {
                    select.innerHTML += `<option value="${d[idField]}">${d[nameField]}</option>`;
                });

                // 🔥 volver a activar select2
                if (['municipio', 'localidad', 'recinto'].includes(select.id)) {
                    $(select).select2({
                        placeholder: "Buscar...",
                        width: '100%'
                    });
                }

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

        fetch(`/supervisor/editar/${id}`)
            .then(r => r.text())
            .then(html => {

                document.getElementById('contenido_modal').innerHTML = html;

                let modal = new bootstrap.Modal(document.getElementById('modalEditar'));
                modal.show();

                // 🔥 AQUÍ ESTÁ LA CLAVE
                activarSumas();
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

    $(document).on('change.select2 change', '#municipio', function() {

        let id = $(this).val();

        // 🔥 limpiar SIN disparar change
        $('#localidad').html('<option value="">-seleccionar-</option>');
        $('#recinto').html('<option value="">-seleccionar-</option>');

        if (!id) return;

        cargar('/localidades/' + id, document.getElementById('localidad'), 'id_localidad', 'nombre');

    });

    $(document).on('change.select2 change', '#localidad', function() {

        let id = $(this).val();

        if (!id) return;

        cargar('/recintos/' + id, document.getElementById('recinto'), 'id_recinto', 'nombre');

    });


    // 🔥 RECINTO → MESAS
    $(document).on('change', '#recinto', function() {

        let recinto = $(this).val();
        let tipo = $('#tipo').val();

        if (!recinto) return;

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

                    // ✅ notificación
                    Swal.fire('Correcto', 'Datos actualizados', 'success');
                    // alert('Datos actualizados');

                    // 🔥 cerrar modal correctamente
                    let modalElement = document.getElementById('modalEditar');
                    let modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.hide();

                    // 🔥 recargar tabla sin refresh
                    document.getElementById('recinto').dispatchEvent(new Event('change'));

                });
        }

    });
</script>