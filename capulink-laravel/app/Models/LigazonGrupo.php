<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LigazonGrupo extends Pivot
{
    protected $table = 'grupo_ligazon';
    public $incrementing = true;
    protected $primary_key = 'id';

    public function grupos()
    {

    }

    public function ligazons()
    {

    }
}
