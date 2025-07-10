<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;

class ProduitSeeder extends Seeder
{
    public function run()
    {
        Produit::create([
            'nom' => 'Produit 1',
            'description' => 'Description du produit 1',
            'prix_achat' => 10.00,
            'prix_vente' => 15.00,
            'categorie_id' => 1,  // Assure-toi que la catégorie existe
        ]);

        Produit::create([
            'nom' => 'Produit 2',
            'description' => 'Description du produit 2',
            'prix_achat' => 12.00,
            'prix_vente' => 18.00,
            'categorie_id' => 1,  // Assure-toi que la catégorie existe
        ]);
    }
}
