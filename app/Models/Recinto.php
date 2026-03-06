<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recinto extends Model
{
    protected $table = 'recintos';

    protected $primaryKey = 'id_recinto';

    protected $fillable = [
        'nombre',
        'direccion',
        'id_municipio'
    ];
}
