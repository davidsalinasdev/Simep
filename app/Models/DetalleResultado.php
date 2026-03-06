<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleResultado extends Model
{
    protected $table = 'detalle_resultado';

    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_resultado',
        'id_candidato',
        'votos'
    ];
}
