<?php

namespace App\Http\Requests;

use App\Rules\StoreLigazonUsuarioUnique;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;

class StoreLigazonUsuarioRequest extends FormRequest
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
        return [
            'idCategoria' => 'nullable|integer|exists:categorias,id',
            'titulo' => 'required|string|max:255',
            'agochado' => 'required|boolean',
            'apropiado' => 'required|boolean',
            'url' => ['required', new StoreLigazonUsuarioUnique(auth()->id())],
            'descricion' => 'nullable|string|max:1000',
            'etiquetas' => 'nullable|array',
            'etiquetas.*' => 'string|max:50', // Cada etiqueta debe ser unha cadea de texto
        ];
    }

    public function messages(): array
    {
        return [
            'idCategoria.exists' => 'A categoría proporcionada non existe.',
            'titulo.required' => 'O título é obrigatorio.',
            'url.required' => 'A URL é obrigatoria.',
            'url.url' => 'A URL debe ter un formato válido.',
            'etiquetas.*.max' => 'Cada etiqueta pode ter un máximo de 50 caracteres.',
        ];
    }

}
