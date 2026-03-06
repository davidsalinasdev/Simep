<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    protected $table = 'candidatos';

    protected $primaryKey = 'id_candidato';

    protected $fillable = [
        'nombre',
        'id_partido',
        'id_cargo',
        'id_municipio'
    ];
}
