<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockToProduitsTable extends Migration
{
    public function up()
    {
        // Vérifiez si la colonne stock existe déjà avant de l'ajouter
        if (!Schema::hasColumn('produits', 'stock')) {
            Schema::table('produits', function (Blueprint $table) {
                $table->integer('stock')->default(50); // Ajout de la colonne stock avec une valeur par défaut
            });
        }
    }

    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
}
