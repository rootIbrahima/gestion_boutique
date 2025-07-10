<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description');
            $table->decimal('prix_achat', 8, 2);
            $table->decimal('prix_vente', 8, 2);
            $table->unsignedBigInteger('categorie_id');  // Clé étrangère pour la catégorie
            $table->integer('stock')->default(50); // Stock par défaut
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
}
