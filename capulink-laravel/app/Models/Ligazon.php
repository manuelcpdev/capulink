<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligazon extends Model
{
    protected $fillable = [
        'titulo', 'descricion', 'url', 'categoria_id', 'visibilidade'
    ];

    // Relación con Categorías (1:N)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    // Relación con Usuarios (ligazóns pertencentes a usuarios)
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_ligazon', 'ligazon_id', 'user_id')
                    ->withPivot(['agochado'])  // Campos extra na táboa intermedia
                    ->withTimestamps();
    }

    // Relación con Grupos (ligazóns pertencentes a grupos)
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_ligazon', 'ligazon_id', 'grupo_id')
                    ->withPivot(['etiqueta_id']) // Campos extra na táboa intermedia
                    ->withTimestamps();
    }

    // Relación con Etiquetas de Usuario (usuario_ligazon_etiqueta)
    public function etiquetasUsuario()
    {
        return $this->belongsToMany(Etiqueta::class, 'usuario_ligazon_etiqueta', 'ligazon_id', 'etiqueta_id')
                    ->withTimestamps();
    }

    // Relación con Etiquetas de Grupo (grupo_ligazon_etiqueta)
    public function etiquetasGrupo()
    {
        return $this->belongsToMany(Etiqueta::class, 'grupo_ligazon_etiqueta', 'ligazon_id', 'etiqueta_id')
                    ->withTimestamps();
    }
}
