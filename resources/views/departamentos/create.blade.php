@extends('layouts.app')

@section('contenido')
<h2>Crear Departamento</h2>

<form method="POST" action="/departamentos">

    @csrf

    <label>Nombre Completo</label>
    <input class="form-control" type="text" name="nombre">

    <button class="btn btn-primary" type="submit">Guardar</button>

</form>
@endsection