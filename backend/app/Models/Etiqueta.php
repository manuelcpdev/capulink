<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    protected $fillable = ['titulo'];
    // Relación moitos a moitos cos grupos
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_etiqueta', 'etiqueta_id', 'grupo_id')
                    ->withTimestamps();
    }

    // Relación moitos a moitos coas ligazóns dun usuario
    public function ligazons()
    {
        return $this->belongsToMany(Ligazon::class, 'usuario_ligazon_etiqueta', 'etiqueta_id', 'ligazon_id')
                    ->withTimestamps();
    }

    // Relación moitos a moitos coas ligazóns dun usuario
    public function ligazonsUsuario()
    {
        return $this->belongsToMany(LigazonUsuario::class, 'usuario_ligazon_etiqueta', 'etiqueta_id', 'ligazon_id')
                    ->withTimestamps();
    }

    // Relación moitos a moitos coas ligazóns dun grupo
    public function ligazonsGrupo()
    {
        return $this->belongsToMany(Ligazon::class, 'grupo_ligazon_etiqueta', 'etiqueta_id', 'ligazon_id')
                    ->withTimestamps();
    }
}




