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
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->string('titulo', 255);
            $table->string('descricion')->nullable();
            $table->boolean('apropiado')->default(true);
            $table->enum('visibilidade', ['publico', 'oculto'])->default('oculto');
            $table->text('url');
            $table->timestamps();

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
