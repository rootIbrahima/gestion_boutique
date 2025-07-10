<?php

namespace App\Http\Controllers\API;

use App\Models\Vente;
use App\Models\VenteProduit;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VenteController extends Controller
{
    // 🔹 Enregistrer une nouvelle vente
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'mode_paiement' => 'required|string',
            'produits' => 'required|array',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $montant_total = 0;

            // Vérification des produits et du stock
            foreach ($request->produits as $item) {
                $produit = Produit::find($item['produit_id']);
                
                // Si le produit n'existe pas
                if (!$produit) {
                    throw new \Exception("Produit avec l'ID {$item['produit_id']} non trouvé.");
                }

                // Log pour vérifier l'état du produit et de son stock
                Log::info("Produit trouvé : {$produit->nom} avec stock actuel : {$produit->stock}");

                // Vérification du stock du produit
                if ($produit->stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour le produit {$produit->nom}. Stock actuel : {$produit->stock}");
                }

                // Calcul du montant total de la vente
                $montant_total += $produit->prix_vente * $item['quantite'];

                // Mise à jour du stock
                $produit->stock -= $item['quantite'];
                $produit->save();
            }

            // Créer la vente
            $vente = Vente::create([
                'client_id' => $request->client_id,
                'montant_total' => $montant_total,
                'mode_paiement' => $request->mode_paiement,
            ]);

            // Ajouter les produits à la vente
            foreach ($request->produits as $item) {
                $produit = Produit::find($item['produit_id']);

                // Enregistrer les produits dans la table VenteProduit
                VenteProduit::create([
                    'vente_id' => $vente->id,
                    'produit_id' => $produit->id,
                    'quantite' => $item['quantite'],
                    'prix_vente' => $produit->prix_vente,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Vente enregistrée avec succès',
                'vente_id' => $vente->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Erreur lors de l'enregistrement de la vente : " . $e->getMessage());

            return response()->json([
                'error' => 'Erreur lors de l’enregistrement de la vente',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
