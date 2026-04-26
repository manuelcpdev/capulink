<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = ['mixto', 'programación', 'videoxogos', 'utilidades'];
        foreach ($categorias as $categoria) {
            if (Categoria::where('titulo', $categoria)->exists()) {
                continue;
            }
            DB::table('categorias')->insert([
                'titulo' => $categoria,
            ]);
        }
    }
}
