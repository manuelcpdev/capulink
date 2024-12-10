<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['titulo'];
    public function ligazons()
    {
        return $this->hasMany(Ligazon::class, 'categoria_id');
    }
}
