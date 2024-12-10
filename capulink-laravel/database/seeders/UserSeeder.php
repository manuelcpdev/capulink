<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perfil; // Importar o modelo Perfil
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'admin', 'email' => 'admin@capulink.com', 'password' => Hash::make('abc123.,'), 'admin' => true],
            ['name' => 'usuario', 'email' => 'usuario@capulink.com', 'password' => Hash::make('abc123.,')],
            ['name' => 'pepe', 'email' => 'pepe@capulink.com', 'password' => Hash::make('abc123.,')],
        ];

        foreach ($users as $userData) {
            // Verificar se o usuario xa existe
            if (User::where('name', $userData['name'])->exists() || User::where('email', $userData['email'])->exists()) {
                continue;
            }

            // Crear o usuario usando o modelo
            $user = User::create($userData);

            // Crear o perfil asociado
            Perfil::create([
                'user_id' => $user->id,
                'visibilidade' => 'publico', // Valor por defecto
                'foto' => null,              // Podes establecer un valor por defecto se queres
            ]);
        }
    }
}
