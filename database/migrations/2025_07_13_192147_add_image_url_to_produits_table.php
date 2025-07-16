<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Vérifier si la colonne image_url existe déjà
        if (!Schema::hasColumn('produits', 'image_url')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->string('image_url')->nullable(); // Ajoute la colonne image_url si elle n'existe pas
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprime la colonne image_url si elle existe
        if (Schema::hasColumn('produits', 'image_url')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }
};
