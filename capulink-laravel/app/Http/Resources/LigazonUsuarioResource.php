<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LigazonUsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->agochado && !$request->user()) {
            return [];
        }
       if($this->agochado && $request->user()->id !== $this->user_id && !$request->user()->admin)
        {
            return [];
        }
        return [
            'id' => $this->id,
            'ligazon_id' => $this->ligazon_id,
            'user_id' => $this->user_id,
            'titulo' => $this->titulo,
            'descricion' =>  $this->descricion,
            //'url' => $this->ligazon->url,
            'url' => $this->whenLoaded('ligazon')->url,
            'apropiado' => $this->apropiado,
            'agochado' => $this->agochado,
            'created_at' =>  $this->created_at,
            'updated_at' =>  $this->updated_at,
            'etiquetas' => $this->whenLoaded('etiquetas'),
        ];
        //return parent::toArray($request);
    }
}
