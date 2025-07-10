<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\VenteController;

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

Route::post('/ventes', [VenteController::class, 'store']);
