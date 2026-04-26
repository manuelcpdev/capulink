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
        Schema::create('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('ligazon_id');
            $table->unsignedBigInteger('etiqueta_id');
            $table->boolean('apropiado')->default(true); // Exemplo: marca se o usuario considera a etiqueta apropiada
            $table->timestamps();

            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
            $table->foreign('ligazon_id')->references('id')->on('ligazons')->onDelete('cascade');
            $table->foreign('etiqueta_id')->references('id')->on('etiquetas')->onDelete('cascade');

            $table->unique(['grupo_id', 'ligazon_id', 'etiqueta_id'], 'grupo_ligazon_etiqueta_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_ligazon_etiqueta');
    }
};
