<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\VenteController;
use App\Http\Controllers\API\CategorieController;


// Routes API pour les produits
Route::prefix('produits')->group(function () {
    Route::get('/', [ProduitController::class, 'index']);      // Tous les produits
    Route::post('/', [ProduitController::class, 'store']);     // Ajouter un produit
    Route::put('{id}', [ProduitController::class, 'update']);  // Modifier un produit
    Route::delete('{id}', [ProduitController::class, 'destroy']); // Supprimer un produit
    
});

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);           // Tous les clients
    Route::post('/', [ClientController::class, 'store']);          // Ajouter un client
    Route::get('{id}', [ClientController::class, 'show']);         // Détails client
    Route::put('{id}', [ClientController::class, 'update']);       // Modifier client
    Route::delete('{id}', [ClientController::class, 'destroy']);   // Supprimer client
});

// Historique des ventes
Route::get('/ventes', [VenteController::class, 'index']); // Récupérer toutes les ventes
Route::post('/ventes', [VenteController::class, 'store']);   // Enregistrer une vente
Route::get('/ventes/{id}', [VenteController::class, 'show']);   // Afficher les détails d'une vente

// Route pour obtenir les KPIs
Route::get('/ventes/kpis', [VenteController::class, 'getKPIs']);

// Route pour obtenir le rapport des ventes par période (ex : mensuel)
Route::get('/ventes/report', [VenteController::class, 'getReport']);

// Route pour obtenir les prévisions de ventes
Route::get('/ventes/forecast', [VenteController::class, 'getForecast']);

Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);  // Récupérer toutes les catégories
    Route::post('/', [CategorieController::class, 'store']); // Ajouter une catégorie
    // Vous pouvez également ajouter d'autres routes pour la mise à jour et la suppression des catégories
});