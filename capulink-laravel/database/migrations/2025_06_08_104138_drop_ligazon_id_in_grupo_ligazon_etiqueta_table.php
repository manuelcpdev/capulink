<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('grupo_ligazon_etiqueta')->truncate();

        // Drop foreign key first
        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->dropForeign(['ligazon_id']);
        });

        // Rename column — requires doctrine/dbal package installed
        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->renameColumn('ligazon_id', 'grupo_ligazon_id');
        });

        // Add new foreign key
        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->foreign('grupo_ligazon_id')
                  ->references('id')
                  ->on('grupo_ligazon')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Reverse steps in down()

        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->dropForeign(['grupo_ligazon_id']);
        });

        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->renameColumn('grupo_ligazon_id', 'ligazon_id');
        });

        Schema::table('grupo_ligazon_etiqueta', function (Blueprint $table) {
            $table->foreign('ligazon_id')
                  ->references('id')
                  ->on('ligazons')  // original reference
                  ->onDelete('cascade');
        });
    }
};
