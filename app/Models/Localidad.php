<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'recintos';

    protected $primaryKey = 'id_recinto';

    protected $fillable = [
        'nombre',
        'id_municipio'
    ];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }
}
