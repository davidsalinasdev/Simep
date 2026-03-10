<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';

    protected $primaryKey = 'id_provincia';

    protected $fillable = [
        'nombre',
        'id_departamento'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }
}
