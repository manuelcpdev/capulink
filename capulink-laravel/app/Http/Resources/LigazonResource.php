<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LigazonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'categoria_id' => $this->categoria,
            'titulo' => $this->titulo,
            'descricion' => $this->descricion,
            'apropiado' => $this->apropiado,
            'visibilidade' => $this->visibilidade,
            'url' => $this->url,
        ];
        //return parent::toArray($request);
    }
}
