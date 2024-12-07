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
        Schema::create('grupo_ligazon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ligazon_id');
            $table->unsignedBigInteger('grupo_id');
            $table->string('titulo');
            $table->string('descricion')->nullable();
            $table->boolean('apropiado')->default(true);
            $table->boolean('agochado')->default(true);
            $table->timestamps();

            $table->foreign('ligazon_id')->references('id')->on('ligazons')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');

            $table->unique(['ligazon_id', 'grupo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_ligazon');
    }
};
