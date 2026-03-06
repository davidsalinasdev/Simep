<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultado extends Model
{
    protected $table = 'resultados';

    protected $primaryKey = 'id_resultado';

    protected $fillable = [
        'id_mesa',
        'id_usuario',
        'imagen_acta',
        'latitud',
        'longitud',
        'estado_validacion'
    ];
}
