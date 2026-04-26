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
        Schema::create('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ligazon_id');
            $table->unsignedBigInteger('etiqueta_id');
            $table->boolean('apropiado')->default(true); // Exemplo: marca se o usuario considera a etiqueta apropiada
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ligazon_id')->references('id')->on('ligazons')->onDelete('cascade');
            $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('cascade');

            $table->unique(['user_id', 'ligazon_id', 'etiqueta_id'], 'usuario_ligazon_etiqueta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_ligazon_etiqueta');
    }
};
