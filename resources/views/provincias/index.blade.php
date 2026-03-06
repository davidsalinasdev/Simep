@extends('layouts.app')

@section('contenido')
<h2>Provincias</h2>

<a href="/provincias/create">Nueva Provincia</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Departamento</th>
    </tr>

    @foreach($provincias as $p)

    <tr>
        <td>{{ $p->id_provincia }}</td>
        <td>{{ $p->nombre }}</td>
        <td>{{ $p->departamento->nombre }}</td>
    </tr>

    @endforeach

</table>
@endsection