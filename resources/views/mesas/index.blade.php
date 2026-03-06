@extends('layouts.app')

@section('contenido')
<h2>Mesas</h2>

<a href="/mesas/create">Nueva Mesa</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Número Mesa</th>
        <th>Recinto</th>
        <th>Estado</th>
    </tr>

    @foreach($mesas as $m)

    <tr>
        <td>{{ $m->id_mesa }}</td>
        <td>{{ $m->numero_mesa }}</td>
        <td>{{ $m->recinto->nombre }}</td>
        <td>{{ $m->estado }}</td>
    </tr>

    @endforeach

</table>
@endsection