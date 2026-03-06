<h2>Crear Cargo</h2>

<form method="POST" action="/cargos">

    @csrf

    <label>Nombre del cargo</label>
    <input type="text" name="nombre_cargo">

    <button type="submit">Guardar</button>

</form>