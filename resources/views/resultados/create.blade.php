<h2>Registrar resultados Mesa {{ $mesa->numero_mesa }}</h2>

<form method="POST" action="/resultados" enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="id_mesa" value="{{ $mesa->id_mesa }}">

    @foreach($candidatos as $candidato)

    <div>
        <label>{{ $candidato->nombre }}</label>
        <input type="number" name="votos[{{ $candidato->id_candidato }}]" value="0">
    </div>

    @endforeach

    <br>

    <label>Blancos</label>
    <input type="number" name="blancos" value="0">

    <br>

    <label>Nulos</label>
    <input type="number" name="nulos" value="0">

    <br>

    <label>Total papeletas</label>
    <input type="number" name="total_papeletas">

    <br>

    <label>Imagen Acta</label>
    <input type="file" name="imagen_acta">

    <br>

    <button type="submit">Guardar Resultado</button>

</form>