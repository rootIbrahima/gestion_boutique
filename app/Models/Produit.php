<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix_achat',
        'prix_vente',
        'categorie_id', // Assurez-vous que cette colonne existe dans la table produits
    ];

    // Relation : Un produit appartient à une catégorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
