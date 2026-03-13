@extends('layouts.app')

@php

use Illuminate\Support\Facades\DB;

$gobernador = DB::table('votos_partido as vp')
->join('partido_cargo as pc','vp.id_partido_cargo','=','pc.id')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Gobernador')
->select(
'p.sigla',
DB::raw('SUM(vp.votos) as votos')
)
->groupBy('p.sigla')
->havingRaw('SUM(vp.votos) > 0') // 👈 ESTA LINEA
->orderByDesc('votos')
->get();


$departamentos = DB::table('departamentos')->orderBy('nombre')->get();
$provincias = DB::table('provincias')->orderBy('nombre')->get();

$asambleista = DB::table('votos_partido as vp')
->join('partido_cargo as pc','vp.id_partido_cargo','=','pc.id')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Asambleista')
->select(
'p.sigla',
DB::raw('SUM(vp.votos) as votos')
)
->groupBy('p.sigla')
->havingRaw('SUM(vp.votos) > 0')
->orderByDesc('votos')
->get();

$labelsAsam = $asambleista->pluck('sigla')->toArray();
$votosAsam = $asambleista->pluck('votos')->toArray();


$asambleista_poblacion = DB::table('votos_partido as vp')
->join('partido_cargo as pc','vp.id_partido_cargo','=','pc.id')
->join('partidos as p','pc.id_partido','=','p.id_partido')
->join('cargos as c','pc.id_cargo','=','c.id_cargo')
->where('c.nombre_cargo','Asambleista Poblacion')
->select(
'p.sigla',
DB::raw('SUM(vp.votos) as votos')
)
->groupBy('p.sigla')
->havingRaw('SUM(vp.votos) > 0')
->orderByDesc('votos')
->get();


$labelsAsamPob = $asambleista_poblacion->pluck('sigla')->toArray();
$votosAsamPob = $asambleista_poblacion->pluck('votos')->toArray();


/* DETALLE */

$detalle = DB::table('votos_especiales as ve')
->join('resultados as r','ve.id_resultado','=','r.id_resultado')
->join('mesas as m','r.id_mesa','=','m.id_mesa')
->join('recintos as re','m.id_recinto','=','re.id_recinto')
->join('localidades as lo','re.id_localidad','=','lo.id_localidad')
->join('municipios as mu','lo.id_municipio','=','mu.id_municipio')
->join('provincias as pr','mu.id_provincia','=','pr.id_provincia')
->join('departamentos as d','pr.id_departamento','=','d.id_departamento')
->where('ve.tipo_eleccion','gobernacion')
->select(

DB::raw('SUM(ve.blancos) as blancos'),
DB::raw('SUM(ve.nulos) as nulos'),
DB::raw('SUM(ve.total_papeletas) as emitidos')

)->first();


$votosValidos = $gobernador->sum('votos');

$porcValidos = $detalle->emitidos > 0 ? ($votosValidos/$detalle->emitidos)*100 : 0;
$porcBlancos = $detalle->emitidos > 0 ? ($detalle->blancos/$detalle->emitidos)*100 : 0;
$porcNulos = $detalle->emitidos > 0 ? ($detalle->nulos/$detalle->emitidos)*100 : 0;


/* DATOS PARA GRAFICO */

$labels = $gobernador->pluck('sigla')->toArray();
$votos = $gobernador->pluck('votos')->toArray();

@endphp


<div class="container-fluid mt-8">
    <div class="d-flex justify-content-between align-items-center">

        <h4 class="mb-3">Resultados Preliminares - SIMEP</h4>

        <div>
            <a href="{{ route('consulta') }}" class="btn btn-primary">
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

		<option value="gobernacion" selected>Gobernador</option>
		<option value="alcaldia">Alcalde</option>

		</select>

		</div>

		<div class="col-md-2">

		<label class="form-label fw-bold">Departamento</label>

		<select class="form-select" id="departamento">

		<option value="">-seleccionar-</option>

		@foreach($departamentos as $dep)

		<option value="{{ $dep->id_departamento }}">
		{{ $dep->nombre }}
		</option>

		@endforeach

		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Provincia</label>

		<select class="form-select" id="provincia">
		<option value="">-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Municipio</label>

		<select class="form-select" id="municipio">
		<option value="">-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Localidad</label>

		<select class="form-select" id="localidad">
		<option value="">-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Recinto</label>

		<select class="form-select" id="recinto">
		<option value="">-seleccionar-</option>
		</select>

		</div>


		</div>
		</div>
  </div>

    <!-- RESULTADOS -->
	<div class="row">
	
    <div class="row">

        <!-- GRAFICO -->

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header fw-bold">
                    Resultados Gráficos Gobernador
                </div>

                <div class="card-body">
                    <canvas id="graficoResultados"></canvas>
                </div>

            </div>
        </div>
		
		<div class="col-lg-4">
			<div class="card shadow">
				<div class="card-header fw-bold">
					Resultados Gráficos Asambleísta
				</div>

				<div class="card-body">
					<canvas id="graficoAsambleista"></canvas>
				</div>

			</div>
		</div>
		
		<div class="col-lg-4" id="panelAsamPoblacion">
			<div class="card shadow">
				<div class="card-header fw-bold">
					Resultados Asambleísta Población
				</div>

				<div class="card-body">
					<canvas id="graficoAsamPoblacion"></canvas>
				</div>

			</div>
		</div>
        


        <!-- PANEL DETALLE -->

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
								<td id="det_validos">{{ number_format($votosValidos) }}</td>
								<td id="det_validos_p">{{ number_format($porcValidos,1) }}%</td>
								</tr>

								<tr>
								<td>Votos Blancos</td>
								<td id="det_blancos">{{ number_format($detalle->blancos) }}</td>
								<td id="det_blancos_p">{{ number_format($porcBlancos,1) }}%</td>
								</tr>

								<tr>
								<td>Votos Nulos</td>
								<td id="det_nulos">{{ number_format($detalle->nulos) }}</td>
								<td id="det_nulos_p">{{ number_format($porcNulos,1) }}%</td>
								</tr>

								<tr>
								<td>Votos Emitidos</td>
								<td id="det_emitidos">{{ number_format($detalle->emitidos) }}</td>
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
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
const labels = {!! json_encode($labels) !!};
const votos = {!! json_encode($votos) !!};

const totalVotos = votos.reduce((a,b)=>a+b,0);

const ctx = document.getElementById('graficoResultados');

const graficoGob = new Chart(ctx, {

    type: 'bar',

    data: {
        labels: labels,
        datasets: [{
            data: votos,
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

        scales: {
            x: {
                beginAtZero: true
            }
        },

        plugins: {
            legend: {
                display: false
            },

            datalabels: {

                anchor: 'center',
                align: 'center',

                color: '#fff',

                font: {
                    weight: 'normal',
                    size: 12
                },

                formatter: function(value){

                    let porcentaje = ((value / totalVotos) * 100).toFixed(1);

                    let texto = value + (value == 1 ? " voto" : " votos");

                    return texto + " (" + porcentaje + "%)";
                }
            }

        }

    },

    plugins: [ChartDataLabels]

});
</script>

<script>

const labelsAsam = {!! json_encode($labelsAsam) !!};
const votosAsam = {!! json_encode($votosAsam) !!};

const labelsAsamPob = {!! json_encode($labelsAsamPob) !!};
const votosAsamPob = {!! json_encode($votosAsamPob) !!};

const totalAsam = votosAsam.reduce((a,b)=>a+b,0);
const totalAsamPobl = votosAsamPob.reduce((a,b)=>a+b,0);

const ctx2 = document.getElementById('graficoAsambleista');

const graficoAsam = new Chart(ctx2, {

    type: 'bar',

    data: {
        labels: labelsAsam,
        datasets: [{
            data: votosAsam,
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

        scales: {
            x: {
                beginAtZero: true
            }
        },

        plugins: {

            legend: {
                display: false
            },

            datalabels: {

                anchor: 'center',
                align: 'center',

                color: '#000',

                font: {
                    size: 12
                },

                formatter: function(value){

                    let porcentaje = ((value / totalAsam) * 100).toFixed(1);

                    return value + " votos (" + porcentaje + "%)";
                }
            }

        }

    },

    plugins: [ChartDataLabels]

});

const ctx3 = document.getElementById('graficoAsamPoblacion');

const graficoAsamPob = new Chart(ctx3, {

    type: 'bar',

    data: {
        labels: labelsAsamPob,
        datasets: [{
            data: votosAsamPob,
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

        scales: {
            x: {
                beginAtZero: true
            }
        },

        plugins: {

            legend: {
                display: false
            },

            datalabels: {

                anchor: 'center',
                align: 'center',

                color: '#000',

                font: {
                    size: 12
                },

                formatter: function(value){

                    let porcentaje = ((value / totalAsamPobl) * 100).toFixed(1);

                    return value + " votos (" + porcentaje + "%)";
                }
            }

        }

    },

    plugins: [ChartDataLabels]

});

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

/* DEPARTAMENTO → PROVINCIA */

$('#departamento').change(function(){

let id = $(this).val();

$.get('/provincias/'+id,function(data){

$('#provincia').html('<option value="">-seleccionar-</option>');
$('#municipio').html('<option value="">-seleccionar-</option>');
$('#localidad').html('<option value="">-seleccionar-</option>');
$('#recinto').html('<option value="">-seleccionar-</option>');

data.forEach(function(p){

$('#provincia').append(
`<option value="${p.id_provincia}">
${p.nombre}
</option>`);

});

});

});


/* PROVINCIA → MUNICIPIO */

$('#provincia').change(function(){

let id = $(this).val();

$.get('/municipios/'+id,function(data){

$('#municipio').html('<option value="">-seleccionar-</option>');
$('#localidad').html('<option value="">-seleccionar-</option>');
$('#recinto').html('<option value="">-seleccionar-</option>');

data.forEach(function(m){

$('#municipio').append(
`<option value="${m.id_municipio}">
${m.nombre}
</option>`);

});

});

});


/* MUNICIPIO → LOCALIDAD */

$('#municipio').change(function(){

let id = $(this).val();

$.get('/localidades/'+id,function(data){

$('#localidad').html('<option value="">-seleccionar-</option>');
$('#recinto').html('<option value="">-seleccionar-</option>');

data.forEach(function(l){

$('#localidad').append(
`<option value="${l.id_localidad}">
${l.nombre}
</option>`);

});

});

});


/* LOCALIDAD → RECINTO */

$('#localidad').change(function(){

let id = $(this).val();

$.get('/recintos/'+id,function(data){

$('#recinto').html('<option value="">-seleccionar-</option>');

data.forEach(function(r){

$('#recinto').append(
`<option value="${r.id_recinto}">
${r.nombre}
</option>`);

});

});

});

</script>

<script>

/* ACTUALIZAR GRAFICOS */

function actualizarGraficos(){
	
	let tipo_eleccion = $('#tipo_eleccion').val();
	console.log(tipo_eleccion);

/* CAMBIAR TITULOS */

if(tipo_eleccion == 'alcaldia'){

$('.card-header').eq(0).text('Resultados Gráficos Alcalde');
$('.card-header').eq(1).text('Resultados Gráficos Concejal');

$('#panelAsamPoblacion').hide(); // ocultar tercer gráfico

}else{

$('.card-header').eq(0).text('Resultados Gráficos Gobernador');
$('.card-header').eq(1).text('Resultados Gráficos Asambleísta');

$('#panelAsamPoblacion').show(); // mostrar tercer gráfico

}
	
	
let departamento = $('#departamento').val();
let provincia = $('#provincia').val();
let municipio = $('#municipio').val();
let localidad = $('#localidad').val();
let recinto = $('#recinto').val();

$.get('/resultados-filtrados',{

tipo_eleccion:tipo_eleccion,
departamento:departamento,
provincia:provincia,
municipio:municipio,
localidad:localidad,
recinto:recinto

},function(data){

/* ORDENAR RESULTADOS */

let datosGob = data.gobernador.sort((a,b)=> b.votos - a.votos);
let datosAsam = data.asambleista.sort((a,b)=> b.votos - a.votos);
let datosAsamPob = data.asambleista_poblacion.sort((a,b)=> b.votos - a.votos);

let labelsGob=[];
let votosGob=[];

let labelsAsam=[];
let votosAsam=[];

let labelsAsamPob=[];
let votosAsamPob=[];


/* GOBERNADOR */

datosGob.forEach(function(item){

labelsGob.push(item.sigla);
votosGob.push(parseInt(item.votos));

});

if(labelsGob.length == 0){
labelsGob = ['Sin votos'];
votosGob = [0];
}


/* ASAMBLEISTA */

datosAsam.forEach(function(item){

labelsAsam.push(item.sigla);
votosAsam.push(parseInt(item.votos));

});

if(labelsAsam.length == 0){
labelsAsam = ['Sin votos'];
votosAsam = [0];
}

/* ASAMBLEISTA POBLACION */

datosAsamPob.forEach(function(item){

labelsAsamPob.push(item.sigla);
votosAsamPob.push(parseInt(item.votos));

});

if(labelsAsamPob.length == 0){
labelsAsamPob = ['Sin votos'];
votosAsamPob = [0];
}


/* ACTUALIZAR GRAFICO GOBERNADOR */

graficoGob.data.labels = labelsGob;
graficoGob.data.datasets[0].data = votosGob;
graficoGob.update();


/* ACTUALIZAR GRAFICO ASAMBLEISTA */

graficoAsam.data.labels = labelsAsam;
graficoAsam.data.datasets[0].data = votosAsam;
graficoAsam.update();

/* ACTUALIZAR ASAMBLEISTA POBLACION */

graficoAsamPob.data.labels = labelsAsamPob;
graficoAsamPob.data.datasets[0].data = votosAsamPob;
graficoAsamPob.update();


/* ACTUALIZAR DETALLE */

let blancos = parseInt(data.detalle.blancos ?? 0);
let nulos = parseInt(data.detalle.nulos ?? 0);
let emitidos = parseInt(data.detalle.emitidos ?? 0);

let validos = votosGob.reduce((a,b)=>a+b,0);

let p_validos = emitidos>0 ? ((validos/emitidos)*100).toFixed(1) : 0;
let p_blancos = emitidos>0 ? ((blancos/emitidos)*100).toFixed(1) : 0;
let p_nulos = emitidos>0 ? ((nulos/emitidos)*100).toFixed(1) : 0;


/* ESCRIBIR EN PANEL */

$('#det_validos').text(validos);
$('#det_validos_p').text(p_validos+'%');

$('#det_blancos').text(blancos);
$('#det_blancos_p').text(p_blancos+'%');

$('#det_nulos').text(nulos);
$('#det_nulos_p').text(p_nulos+'%');

$('#det_emitidos').text(emitidos);

});

}


/* CUANDO CAMBIA UN FILTRO */
$('#tipo_eleccion').change(actualizarGraficos);
$('#departamento').change(actualizarGraficos);
$('#provincia').change(actualizarGraficos);
$('#municipio').change(actualizarGraficos);
$('#localidad').change(actualizarGraficos);
$('#recinto').change(actualizarGraficos);

if(tipo_eleccion == 'alcaldia'){

$('.card-header:contains("Gobernador")').text('Resultados Gráficos Alcalde');
$('.card-header:contains("Asambleísta")').text('Resultados Gráficos Concejal');

}else{

$('.card-header:contains("Alcalde")').text('Resultados Gráficos Gobernador');
$('.card-header:contains("Concejal")').text('Resultados Gráficos Asambleísta');

}


</script>