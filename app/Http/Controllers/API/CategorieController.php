<?php

namespace App\Http\Controllers\API;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategorieController extends Controller
{
    // Fonction pour récupérer toutes les catégories
    public function index()
    {
        // Récupère toutes les catégories et les renvoie en JSON
        $categories = Categorie::all();
        return response()->json($categories);
    }

    // Fonction pour créer une nouvelle catégorie (si nécessaire)
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $categorie = Categorie::create([
            'nom' => $request->nom,
        ]);

        return response()->json($categorie, 201);
    }

    // Autres méthodes pour gérer les catégories (update, delete, etc.)
}
