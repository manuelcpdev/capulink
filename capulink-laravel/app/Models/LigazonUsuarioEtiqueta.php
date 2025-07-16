<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigazonUsuarioEtiqueta extends Model
{
    protected $table = "usuario_ligazon_etiqueta";
    protected $fillable = ['user_id', 'usuario_ligazon_id', 'etiqueta_id'];
    //

    public function etiqueta(): BelongsTo
    {
        return $this->belongsTo(Etiqueta::class, 'etiqueta_id', 'id');
    }

    public function ligazon(): BelongsTo
    {
        return $this->belongsTo(LigazonUsuario::class);
    }
}
