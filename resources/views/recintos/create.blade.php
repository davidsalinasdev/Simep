<h2>Crear Recinto</h2>

<form method="POST" action="/recintos">

    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <br><br>

    <label>Dirección</label>
    <input type="text" name="direccion">

    <br><br>

    <label>Municipio</label>

    <select name="id_municipio">

        @foreach($municipios as $m)

        <option value="{{ $m->id_municipio }}">
            {{ $m->nombre }}
        </option>

        @endforeach

    </select>

    <br><br>

    <button type="submit">Guardar</button>

</form>