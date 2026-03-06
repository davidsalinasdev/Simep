@extends('layouts.app')

@section('contenido')
<h1>Resultados Electorales</h1>

<p>Total mesas: {{ $mesas_total }}</p>
<p>Mesas registradas: {{ $mesas_enviadas }}</p>
<p>Avance: {{ number_format($porcentaje,2) }} %</p>

<hr>

<table border="1">

    <tr>
        <th>Candidato</th>
        <th>Partido</th>
        <th>Votos</th>
    </tr>

    @foreach($resultados as $r)

    <tr>
        <td>{{ $r->candidato }}</td>
        <td>{{ $r->partido }}</td>
        <td>{{ $r->total_votos }}</td>
    </tr>

    @endforeach


</table>
@endsection