@extends('layouts.app')

@section('contenido')
<h2>Municipios</h2>

<a href="/municipios/create">Nuevo Municipio</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Provincia</th>
    </tr>

    @foreach($municipios as $m)

    <tr>
        <td>{{ $m->id_municipio }}</td>
        <td>{{ $m->nombre }}</td>
        <td>{{ $m->provincia->nombre }}</td>
    </tr>

    @endforeach

</table>
@endsection