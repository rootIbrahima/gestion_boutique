<?php

namespace App\Http\Controllers\API;

use App\Models\Vente;
use App\Models\VenteProduit;
use App\Models\Produit;
use App\Models\Client;
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
                
                if (!$produit) {
                    throw new \Exception("Produit avec l'ID {$item['produit_id']} non trouvé.");
                }

                if ($produit->stock < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour le produit {$produit->nom}. Stock actuel : {$produit->stock}");
                }

                // Calcul du montant total
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

    // 🔹 Récupérer l'historique des ventes
    public function index()
    {
        $ventes = Vente::with('client')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($ventes);
    }

    // 🔹 Détails d'une vente
    public function show($id)
    {
        $vente = Vente::with(['produits.produit', 'client'])
            ->findOrFail($id);

        return response()->json($vente);
    }

    // 🔹 Récupérer les KPIs
    public function getKPIs()
    {
        // Calcul des KPIs
        $totalSales = Vente::sum('montant_total'); // Total des ventes
        $totalProductsSold = VenteProduit::sum('quantite'); // Total des produits vendus
        $totalClients = Client::count(); // Nombre total de clients

        return response()->json([
            'total_sales' => $totalSales,
            'total_products_sold' => $totalProductsSold,
            'total_clients' => $totalClients,
        ]);
    }

    // 🔹 Récupérer le rapport des ventes par période
    public function getReport(Request $request)
    {
        $period = $request->query('period', 'monthly');
        $salesReport = [];

        // Rapport des ventes mensuelles
        if ($period == 'monthly') {
            $salesReport = Vente::selectRaw('SUM(montant_total) as total_sales, MONTH(created_at) as month')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get();
        }

        return response()->json($salesReport);
    }

    // 🔹 Prévisions des ventes
    public function getForecast()
    {
        // Données de ventes par mois
        $salesData = Vente::selectRaw('MONTH(created_at) as month, SUM(montant_total) as total_sales')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Calcul de la moyenne des ventes passées
        $averageSales = $salesData->avg('total_sales');

        return response()->json([
            'average_sales' => $averageSales,
            'forecast' => $averageSales * 1.2 // Prédiction 20% au-dessus de la moyenne
        ]);
    }
}
