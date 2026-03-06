<h2>Crear Provincia</h2>

<form method="POST" action="/provincias">

    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <br><br>

    <label>Departamento</label>

    <select name="id_departamento">

        @foreach($departamentos as $d)

        <option value="{{ $d->id_departamento }}">
            {{ $d->nombre }}
        </option>

        @endforeach

    </select>

    <br><br>

    <button type="submit">Guardar</button>

</form>