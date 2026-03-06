@extends('layouts.app')

@section('contenido')
<h2>Recintos</h2>

<a href="/recintos/create">Nuevo Recinto</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Municipio</th>
    </tr>

    @foreach($recintos as $r)

    <tr>
        <td>{{ $r->id_recinto }}</td>
        <td>{{ $r->nombre }}</td>
        <td>{{ $r->municipio->nombre }}</td>
    </tr>

    @endforeach

</table>
@endsection