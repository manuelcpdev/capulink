<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ligazons', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('categoria_id');
            $table->string('titulo', 255); // Título cun límite de 255 caracteres
            $table->string('descricion')->nullable();
            $table->boolean('apropiado')->default(true); // Indica se a ligazón é apropiada
            $table->enum('visibilidade', ['publico', 'oculto'])->default('oculto');
            $table->string('url', 191)->unique(); // URL cun límite de 2048 caracteres
            $table->timestamps(); // Created_at e updated_at

            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligazons');
    }
};
