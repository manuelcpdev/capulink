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
        Schema::create('usuario_ligazon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ligazon_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('agochado')->default(true);
            $table->timestamps();

            $table->foreign('ligazon_id')->references('id')->on('ligazons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['ligazon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_ligazon');
    }
};
