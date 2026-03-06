@extends('layouts.app')

@section('contenido')
<h2 class="text-alert-success">Departamentos</h2>

<a href="/departamentos/create">Nuevo Departamento</a>

<table border="1">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
    </tr>

    @foreach($departamentos as $d)

    <tr>
        <td>{{ $d->id_departamento }}</td>
        <td>{{ $d->nombre }}</td>
    </tr>

    @endforeach

</table>
@endsection