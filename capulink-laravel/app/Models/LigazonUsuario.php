<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LigazonUsuario extends Pivot
{
    protected $table = 'usuario_ligazon';
    public $incrementing = true;
    protected $fillable = ['titulo', 'descricion', 'ligazon_id', 'user_id', 'agochado'];

    /**
     * Get the user that owns the LigazonUsuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /*
    public function etiquetas()
    {
        return $this->hasManyThrough(
            Etiqueta::class,
            LigazonUsuarioEtiqueta::class,
            'usuario_ligazon_id', // FK on intermediate table
            'id',         // FK on etiqueta table
            'ligazon_id', // local key on LigazonUsuario (or get from $this->ligazon)
            'etiqueta_id' // FK on UsuarioLigazonEtiqueta
        );
    }
        */
    public function etiquetas()
    {
        return $this->belongsToMany(
            Etiqueta::class,
            'usuario_ligazon_etiqueta',
            'usuario_ligazon_id',
            'etiqueta_id'
        )
            ->withPivot('user_id', 'apropiado')
            ->withTimestamps();
    }
    /**
     * Unha ligazón pode ter unha ou máis etiquetas en LigazonUsuarioEtiqueta
     * Unha LigazonUsuarioEtiqueta (teñe un ID propio) só pode ser dunha LigazonUsuario
     */
    public function etiqueta()
    {
        return $this->hasMany(LigazonUsuarioEtiqueta::class, 'usuario_ligazon_id', 'etiqueta_id');
    }

    public function ligazon()
    {
        return $this->hasOne(Ligazon::class, 'id', 'ligazon_id');
    }
}
