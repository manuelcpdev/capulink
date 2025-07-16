<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('usuario_ligazon_etiqueta')->truncate();

        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->dropForeign(['ligazon_id']);
        });

        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->renameColumn('ligazon_id', 'usuario_ligazon_id');
        });

        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->foreign('usuario_ligazon_id')->references('id')->on('usuario_ligazon')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->dropForeign(['usuario_ligazon_id']);
        });

        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->renameColumn('usuario_ligazon_id', 'ligazon_id');
        });

        Schema::table('usuario_ligazon_etiqueta', function (Blueprint $table) {
            $table->foreign('ligazon_id')->references('id')->on('ligazons')->onDelete('cascade');
        });
    }
};
