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
)->first();


$votosValidos = $gobernador->sum('votos');

// 🔥 calcular emitidos correctamente
$emitidos = $votosValidos + $detalle->blancos + $detalle->nulos;

// 🔥 porcentajes correctos
$porcValidos = $emitidos > 0 ? ($votosValidos/$emitidos)*100 : 0;
$porcBlancos = $emitidos > 0 ? ($detalle->blancos/$emitidos)*100 : 0;
$porcNulos = $emitidos > 0 ? ($detalle->nulos/$emitidos)*100 : 0;


/* DATOS PARA GRAFICO */

$labels = $gobernador->pluck('sigla')->toArray();
$votos = $gobernador->pluck('votos')->toArray();

@endphp

<style>
td {
    vertical-align: middle;
}
td:nth-child(2), td:nth-child(3){
    text-align: right;
}
</style>

<div class="container-fluid mt-8">
    <div class="d-flex justify-content-between align-items-center">	

        <h4 class="mb-3 d-none d-md-block" >Resultados Preliminares</h4>
		
		<h4 class="mb-3 d-md-none" >SIMEP</h4>
		

        <div>
            <a href="{{ route('consulta') }}" class="btn btn-primary">
                <i class="fas fa-sync-alt"></i> Actualizar
            </a>
        </div>

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

		<option value="" disabled selected>-seleccionar-</option>

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
		<option value="" disabled selected>-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Municipio</label>

		<select class="form-select" id="municipio">
		<option value="" disabled selected>-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Localidad</label>

		<select class="form-select" id="localidad">
		<option value="" disabled selected>-seleccionar-</option>
		</select>

		</div>



		<div class="col-md-2">

		<label class="form-label fw-bold">Recinto</label>

		<select class="form-select" id="recinto">
		<option value="" disabled selected>-seleccionar-</option>
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
                    <div style="height:400px; overflow-y:auto;">
    <canvas id="graficoResultados"></canvas>
</div>
					<div class="card mt-2">
					<div class="card-header bg-danger text-white">Detalle Gobernador</div>
					<div class="card-body">
						<table class="table table-sm mb-0">
							<tr>
								<td>Válidos</td>
								<td id="g_validos"></td>
								<td id="g_validos_p"></td>
							</tr>
							<tr>
								<td>Blancos</td>
								<td id="g_blancos"></td>
								<td id="g_blancos_p"></td>
							</tr>
							<tr>
								<td>Nulos</td>
								<td id="g_nulos"></td>
								<td id="g_nulos_p"></td>
							</tr>
							<tr>
								<td><b>Emitidos</b></td>
								<td id="g_emitidos"></td>
							</tr>
						</table>
					</div>
				</div>
					
                </div>

            </div>
        </div>
		
		<div class="col-lg-4">
			<div class="card shadow">
				<div class="card-header fw-bold" id="titulo_grafico_2">
					Resultados Gráficos Asambleísta
				</div>

				<div class="card-body">
					<div style="height:400px; overflow-y:auto;">
    <canvas id="graficoAsambleista"></canvas>
</div>
					<div class="card mt-2">
					<div class="card-header bg-primary text-white" id="titulo_detalle_2">
						Detalle Asambleísta
					</div>
					<div class="card-body">
						<table class="table table-sm mb-0">
							<tr>
								<td>Válidos</td>
								<td id="a_validos"></td>
								<td id="a_validos_p"></td>
							</tr>
							<tr>
								<td>Blancos</td>
								<td id="a_blancos"></td>
								<td id="a_blancos_p"></td>
							</tr>
							<tr>
								<td>Nulos</td>
								<td id="a_nulos"></td>
								<td id="a_nulos_p"></td>
							</tr>
							<tr>
								<td><b>Emitidos</b></td>
								<td id="a_emitidos"></td>
							</tr>
						</table>
					</div>
				</div>
				</div>

			</div>
		</div>
		
		<div class="col-lg-4" id="panelAsamPoblacion">
			<div class="card shadow">
				<div class="card-header fw-bold">
					Resultados Asambleísta Población
				</div>

				<div class="card-body">
				<div style="height:400px; overflow-y:auto;">
    <canvas id="graficoAsamPoblacion"></canvas>
</div>
					<div class="card mt-2">
					<div class="card-header bg-warning text-white">Detalle Asambleísta Población</div>
					<div class="card-body">
						<table class="table table-sm mb-0">
							<tr>
								<td>Válidos</td>
								<td id="ap_validos"></td>
								<td id="ap_validos_p"></td>
							</tr>
							<tr>
								<td>Blancos</td>
								<td id="ap_blancos"></td>
								<td id="ap_blancos_p"></td>
							</tr>
							<tr>
								<td>Nulos</td>
								<td id="ap_nulos"></td>
								<td id="ap_nulos_p"></td>
							</tr>
							<tr>
								<td><b>Emitidos</b></td>
								<td id="ap_emitidos"></td>
							</tr>
						</table>
					</div>
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
     responsive: true,
     maintainAspectRatio: false, // 🔥 OBLIGATORIO
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

				anchor: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'end';
				},

				align: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'right';
				},

				color: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? '#fff' : '#000';
				},

				offset: 8,

				font: {
					size: 12,
					weight: 'bold'
				},

				formatter: function(value, context){

					let total = context.dataset.data.reduce((a,b)=>a+b,0);
					let porcentaje = ((value / total) * 100).toFixed(1);

					return value + " votos (" + porcentaje + "%)";
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
		responsive: true,
		maintainAspectRatio: false, // 🔥 OBLIGATORIO
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

				anchor: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'end';
				},

				align: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'right';
				},

				color: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? '#fff' : '#000';
				},

				offset: 8,

				font: {
					size: 12,
					weight: 'bold'
				},

				formatter: function(value, context){

					let total = context.dataset.data.reduce((a,b)=>a+b,0);
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
		responsive: true,
        maintainAspectRatio: false, // 🔥 OBLIGATORIO

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

				anchor: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'end';
				},

				align: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? 'center' : 'right';
				},

				color: function(context){
					let value = context.dataset.data[context.dataIndex];
					let max = Math.max(...context.dataset.data);

					return value > (max * 0.7) ? '#fff' : '#000';
				},

				offset: 8,

				font: {
					size: 12,
					weight: 'bold'
				},

				formatter: function(value, context){

					let total = context.dataset.data.reduce((a,b)=>a+b,0);
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

    $('#titulo_grafico_2').text('Resultados Gráficos Concejal');
    $('#titulo_detalle_2').text('Detalle Concejal');

    $('.card-header').eq(0).text('Resultados Gráficos Alcalde');

    $('#panelAsamPoblacion').hide();

}else{

    $('#titulo_grafico_2').text('Resultados Gráficos Asambleísta');
    $('#titulo_detalle_2').text('Detalle Asambleísta');

    $('.card-header').eq(0).text('Resultados Gráficos Gobernador');

    $('#panelAsamPoblacion').show();
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

function pintarDetalle(det, votosArray, pref){

    let blancos = parseInt(det?.blancos ?? 0);
    let nulos = parseInt(det?.nulos ?? 0);

    let validos = votosArray.reduce((a,b)=>a+b,0);
    let emitidos = validos + blancos + nulos;

    let pValidos = emitidos > 0 ? ((validos/emitidos)*100).toFixed(1) : 0;
    let pBlancos = emitidos > 0 ? ((blancos/emitidos)*100).toFixed(1) : 0;
    let pNulos = emitidos > 0 ? ((nulos/emitidos)*100).toFixed(1) : 0;

    // 🔥 valores
    $('#'+pref+'_validos').text(validos);
    $('#'+pref+'_blancos').text(blancos);
    $('#'+pref+'_nulos').text(nulos);
    $('#'+pref+'_emitidos').text(emitidos);

    // 🔥 porcentajes
    $('#'+pref+'_validos_p').text(pValidos + '%');
    $('#'+pref+'_blancos_p').text(pBlancos + '%');
    $('#'+pref+'_nulos_p').text(pNulos + '%');
}

/* LLAMADAS */

if(tipo_eleccion == 'alcaldia'){

    pintarDetalle(data.detalle_alcalde, votosGob, 'g'); // 🔥 alcalde
    pintarDetalle(data.detalle_concejal, votosAsam, 'a'); // 🔥 concejal

}else{

    pintarDetalle(data.detalle_gob, votosGob, 'g');
    pintarDetalle(data.detalle_asam, votosAsam, 'a');
    pintarDetalle(data.detalle_asam_pob, votosAsamPob, 'ap');

}


/* ESCRIBIR EN PANEL */



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

$(document).ready(function(){
    actualizarGraficos();
});


</script>