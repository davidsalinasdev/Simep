<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotoEspecial extends Model
{
    protected $table = 'votos_especiales';

    protected $fillable = [
        'id_resultado',
        'blancos',
        'nulos',
        'total_papeletas'
    ];
}
