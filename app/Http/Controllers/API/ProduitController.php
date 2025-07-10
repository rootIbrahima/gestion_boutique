<?php

namespace App\Http\Controllers\API;

use App\Models\Produit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProduitController extends Controller
{
    // Créer un produit avec un stock initial
    public function store(Request $request)
    {
        // Validation des données reçues
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'prix_achat' => 'required|numeric',
            'prix_vente' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0', // stock est optionnel mais doit être positif si fourni
        ]);

        try {
            // Si le stock n'est pas renseigné, on le met à 50 par défaut
            $validated['stock'] = $validated['stock'] ?? 50;

            // Créer le produit dans la base de données
            $produit = Produit::create([
                'nom' => $validated['nom'],
                'description' => $validated['description'],
                'prix_achat' => $validated['prix_achat'],
                'prix_vente' => $validated['prix_vente'],
                'categorie_id' => $validated['categorie_id'],
                'stock' => $validated['stock'], // Assigner le stock
            ]);

            return response()->json([
                'message' => 'Produit ajouté avec succès!',
                'produit' => $produit
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'ajout du produit',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // Liste tous les produits
    public function index()
    {
        $produits = Produit::all();
        return response()->json($produits);
    }

    // Afficher un produit par son ID
    public function show($id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
        return response()->json($produit);
    }

    // Modifier un produit
    public function update(Request $request, $id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'prix_achat' => 'required|numeric',
            'prix_vente' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0', // stock est optionnel mais doit être positif si fourni
        ]);

        $produit->update($validated);

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'produit' => $produit
        ]);
    }

    // Supprimer un produit
    public function destroy($id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
        $produit->delete();
        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}
