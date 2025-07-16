<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLigazonUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return  [
            'ligazon_id' => 'required|integer|exists:usuario_ligazon,ligazon_id',
            'titulo' => 'nullable|string|max:255',
            'descricion' => 'nullable|string|max:1000',
            'agochado' => 'nullable|boolean',
            'apropiado' => 'nullable|boolean',
            'etiquetas_agregar' => 'nullable|array', // Etiquetas para engadir
            'etiquetas_agregar.*' => 'string|max:50',
            'etiquetas_eliminar' => 'nullable|array', // Etiquetas para eliminar
            'etiquetas_eliminar.*' => 'string|max:50',
            'etiquetas' => 'nullable',
        ];
    }

    public function messages() {
        return [
            'ligazon_id.required' => 'O ID da ligazón é obrigatorio.',
            'ligazon_id.exists' => 'A ligazón especificada non existe.',
            'titulo.string' => 'O título debe ser unha cadea de texto.',
            'titulo.max' => 'O título non pode ter máis de 255 caracteres.',
            'descricion.string' => 'A descrición debe ser unha cadea de texto.',
            'descricion.max' => 'A descrición non pode ter máis de 1000 caracteres.',
            'etiquetas_agregar.*.max' => 'Cada etiqueta a engadir pode ter un máximo de 50 caracteres.',
            'etiquetas_eliminar.*.max' => 'Cada etiqueta a eliminar pode ter un máximo de 50 caracteres.',
        ];
    }
}
