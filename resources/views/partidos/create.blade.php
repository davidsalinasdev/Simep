<h2>Crear Partido</h2>

<form method="POST" action="/partidos">

    @csrf

    <label>Nombre</label>
    <input type="text" name="nombre">

    <label>Sigla</label>
    <input type="text" name="sigla">

    <label>Color</label>
    <input type="text" name="color">

    <button type="submit">Guardar</button>

</form>