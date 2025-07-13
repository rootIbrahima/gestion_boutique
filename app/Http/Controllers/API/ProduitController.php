<?php

namespace App\Http\Controllers\API;

use App\Models\Produit;
use App\Models\Categorie; // Assurez-vous d'inclure le modèle Categorie
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Utiliser Storage pour déplacer l'image de manière sécurisée

class ProduitController extends Controller
{
    // 🔹 Créer un produit avec un stock initial
    public function store(Request $request)
    {
        // Validation des données reçues
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'prix_achat' => 'required|numeric',
            'prix_vente' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id', // Assurez-vous que la catégorie existe
            'stock' => 'nullable|integer|min:0', // stock est optionnel mais doit être positif si fourni
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation pour l'image
        ]);

        try {
            // Si le stock n'est pas renseigné, on le met à 50 par défaut
            $validated['stock'] = $validated['stock'] ?? 50;

            // Traiter l'upload de l'image si elle existe
            $image_url = null; // Valeur par défaut pour l'image_url
            if ($request->hasFile('image')) {
                // Récupérer le fichier image
                $image = $request->file('image');

                // Vérifier si l'image est valide
                if ($image->isValid()) {
                    // Générer un nom unique pour l'image
                    $imageName = time() . '.' . $image->extension();

                    // Déplacer l'image dans le dossier public/images
                    $image->move(public_path('images'), $imageName);

                    // Sauvegarder le chemin de l'image dans la base de données
                    $image_url = 'images/' . $imageName;
                } else {
                    throw new \Exception('Erreur lors du téléchargement de l\'image');
                }
            }

            // Créer le produit dans la base de données
            $produit = Produit::create([
                'nom' => $validated['nom'],
                'description' => $validated['description'],
                'prix_achat' => $validated['prix_achat'],
                'prix_vente' => $validated['prix_vente'],
                'categorie_id' => $validated['categorie_id'],
                'stock' => $validated['stock'], // Assigner le stock
                'image_url' => $image_url,  // Ajouter l'URL de l'image si elle existe
            ]);

            // Charger la catégorie associée au produit
            $produit->load('categorie');

            // Retourner une réponse avec le produit ajouté
            return response()->json([
                'message' => 'Produit ajouté avec succès!',
                'produit' => $produit
            ], 201);

        } catch (\Exception $e) {
            // En cas d'erreur, retourner une erreur avec le message
            return response()->json([
                'error' => 'Erreur lors de l\'ajout du produit',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // 🔹 Liste tous les produits
    public function index()
    {
        // Récupère tous les produits avec leurs catégories associées
        $produits = Produit::with('categorie')->get();

        // Retourner tous les produits sous forme de JSON
        return response()->json($produits);
    }

    // 🔹 Afficher un produit par son ID
    public function show($id)
    {
        // Trouver le produit avec son ID, et inclure la catégorie associée
        $produit = Produit::with('categorie')->find($id);
        
        // Si le produit n'est pas trouvé
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        // Retourner les détails du produit
        return response()->json($produit);
    }

    // 🔹 Modifier un produit
    public function update(Request $request, $id)
    {
        // Trouver le produit par son ID
        $produit = Produit::find($id);

        // Si le produit n'est pas trouvé
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        // Validation des données reçues pour la mise à jour
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'prix_achat' => 'required|numeric',
            'prix_vente' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id',
            'stock' => 'nullable|integer|min:0',
        ]);

        // Mise à jour des informations du produit
        $produit->update($validated);

        // Charger la catégorie mise à jour
        $produit->load('categorie');

        // Retourner une réponse avec le produit mis à jour
        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'produit' => $produit
        ]);
    }

    // 🔹 Supprimer un produit
    public function destroy($id)
    {
        // Trouver le produit par son ID
        $produit = Produit::find($id);

        // Si le produit n'est pas trouvé
        if (!$produit) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        // Supprimer le produit
        $produit->delete();

        // Retourner une réponse confirmant la suppression
        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}
