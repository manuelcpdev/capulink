<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligazon extends Model
{
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_ligazon', 'ligazon_id', 'grupo_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'usuario_ligazon', 'ligazon_id', 'user_id');
    }

    public function categoria()
    {
        return $this->belongTo(Categoria::class);
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'usuario_ligazon_etiqueta', 'ligazon_id', 'etiqueta_id');
    }

    public function etiquetasGrupo()
    {
        return $this->belongsToMany(Etiqueta::class, 'grupo_ligazon_etiqueta', 'ligazon_id', 'etiqueta_id')
                    ->withTimestamps();
    }

}
