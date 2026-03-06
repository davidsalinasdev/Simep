<h2>Crear Candidato</h2>

<form method="POST" action="/candidatos">

    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <label>Partido</label>
    <select name="id_partido">

        @foreach($partidos as $p)

        <option value="{{ $p->id_partido }}">
            {{ $p->nombre }}
        </option>

        @endforeach

    </select>

    <label>Cargo</label>
    <select name="id_cargo">

        @foreach($cargos as $c)

        <option value="{{ $c->id_cargo }}">
            {{ $c->nombre_cargo }}
        </option>

        @endforeach

    </select>

    <button type="submit">Guardar</button>

</form>