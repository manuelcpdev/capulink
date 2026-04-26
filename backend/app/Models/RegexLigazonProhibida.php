<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegexLigazonProhibida extends Model
{
    // Definir a táboa explícitamente se o nome non segue a convención
    protected $table = 'regex_ligazons_prohibdas';

    // Proporción das columnas que se poden modificar
    protected $fillable = [
        'regex',
        'motivo',
    ];

    // Se non usas as columnas `created_at` e `updated_at`, desactívalas
    public $timestamps = true;
}

