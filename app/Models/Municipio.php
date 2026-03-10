<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';

    protected $primaryKey = 'id_municipio';

    protected $fillable = [
        'nombre',
        'id_provincia'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }
}
