<?php

namespace App\Rules;

use App\Models\Ligazon;
use App\Models\LigazonUsuario;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreLigazonUsuarioUnique implements ValidationRule
{
    private $userId = null;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ligazon = Ligazon::where('url', $value)->first();

        if($ligazon) {
            $ligazon_usuario = LigazonUsuario::where('ligazon_id', $ligazon->id)->where('user_id', $this->userId)->first();
            if($ligazon_usuario) {
                $fail('Xa existe :attribute para este usuario');
            }
        }
    }
}
