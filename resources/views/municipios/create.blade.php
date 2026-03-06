<h2>Crear Municipio</h2>

<form method="POST" action="/municipios">

    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <br><br>

    <label>Provincia</label>

    <select name="id_provincia">

        @foreach($provincias as $p)

        <option value="{{ $p->id_provincia }}">
            {{ $p->nombre }}
        </option>

        @endforeach

    </select>

    <br><br>

    <button type="submit">Guardar</button>

</form>