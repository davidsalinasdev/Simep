<h3>Editar Mesa {{ $resultado->id_mesa }}</h3>

<form id="formEditar">
    @csrf

    <input type="hidden" name="id_resultado" value="{{ $resultado->id_resultado }}">

    <div class="accordion">

        @php
        $grupos = $votos->groupBy('nombre_cargo');
        @endphp

        @foreach($grupos as $cargo => $items)

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse">
                    Votos {{ $cargo }}
                </button>
            </h2>

            <div class="accordion-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>Partido</th>
                            <th>Votos</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $v)
                        <tr>
                            <td>
                                <strong>{{ $v->sigla }}</strong><br>
                                <small>{{ $v->nombre }}</small>
                            </td>

                            <td>
                                <input type="number"
                                    name="votos[{{ $v->id }}]"
                                    value="{{ $v->votos }}"
                                    class="form-control sumar {{ strtolower(str_replace(' ','_',$cargo)) }}"
                                    min="0">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @php
                $map = [
                'gobernador' => 'Gobernador',
                'asambleista' => 'Asambleista',
                'asambleista_poblacion' => 'Asambleista Poblacion',
                'alcalde' => 'Alcalde',
                'concejal' => 'Concejal'
                ];

                $key = strtolower(str_replace(' ','_',$cargo));

                $nombreReal = $map[$key] ?? $cargo;

                $esp = $especiales->firstWhere('nombre_cargo', $nombreReal);
                @endphp


                <div class="card p-3">

                    <div class="row">
                        <div class="col-md-4">
                            <label>Blancos</label>
                            <input type="number"
                                name="especial[{{ strtolower(str_replace(' ','_',$cargo)) }}][blancos]"
                                value="{{ $esp->blancos ?? 0 }}"
                                class="form-control sumar {{ strtolower(str_replace(' ','_',$cargo)) }}">
                        </div>

                        <div class="col-md-4">
                            <label>Nulos</label>
                            <input type="number"
                                name="especial[{{ strtolower(str_replace(' ','_',$cargo)) }}][nulos]"
                                value="{{ $esp->nulos ?? 0 }}"
                                class="form-control sumar {{ strtolower(str_replace(' ','_',$cargo)) }}">
                        </div>
                    </div>

                    <div class="mt-2">
                        <label>Total</label>
                        <input type="number"
                            id="total_{{ strtolower(str_replace(' ','_',$cargo)) }}"
                            class="form-control"
                            readonly>
                    </div>

                </div>

            </div>
        </div>

        @endforeach

    </div>

    <button class="btn btn-success w-100 mt-3">
        Guardar Corrección
    </button>

</form>