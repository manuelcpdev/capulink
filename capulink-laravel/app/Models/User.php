<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }

    // Relación moitos a moitos cos grupos aos que pertence o usuario
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'usuario_grupo', 'user_id', 'grupo_id')
            ->withPivot('id','grupo_id', 'user_id')
            ->withTimestamps();
    }

    public function gruposCreados()
    {
        return $this->hasMany(Grupo::class, 'user_id');
    }

    public function ligazonsUsuario()
    {
        return $this->hasMany(LigazonUsuario::class, 'user_id', 'id');
            //->withPivot('titulo','agochado', 'apropiado', 'descricion')
            //->withTimestamps();
    }
}
