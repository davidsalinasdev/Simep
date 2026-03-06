<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    protected $table = 'partidos';

    protected $primaryKey = 'id_partido';

    protected $fillable = [
        'nombre',
        'sigla',
        'color'
    ];
}
