<h2>Crear Mesa</h2>

<form method="POST" action="/mesas">

    @csrf

    <label>Número de Mesa</label>
    <input type="number" name="numero_mesa">

    <br><br>

    <label>Recinto</label>

    <select name="id_recinto">

        @foreach($recintos as $r)

        <option value="{{ $r->id_recinto }}">
            {{ $r->nombre }}
        </option>

        @endforeach

    </select>

    <br><br>

    <button type="submit">Guardar</button>

</form>