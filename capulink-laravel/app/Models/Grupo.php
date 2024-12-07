<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['titulo', 'user_id', 'descricion', 'apropiado']; // 'user_id' indica quen creou o grupo

    // Relación con user (quén creou o grupo)
    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación moitos a moitos con users (users que pertencen ao grupo)
    public function users()
    {
        return $this->belongsToMany(User::class, 'usuario_grupo', 'grupo_id', 'user_id')
            ->withTimestamps();
    }

    // Relación moitos a moitos con ligazóns
    public function ligazons()
    {
        return $this->belongsToMany(Ligazon::class, 'grupo_ligazon', 'grupo_id', 'ligazon_id')
            ->withTimestamps();
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'grupo_etiqueta', 'grupo_id', 'etiqueta_id')
                    ->withTimestamps();  // Para manexar as marcas de tempo
    }

    public function ligazonsEtiquetas()
    {
        return $this->belongsToMany(Etiqueta::class, 'grupo_ligazon_etiqueta', 'grupo_id', 'etiqueta_id')
                    ->withTimestamps();
    }
}
