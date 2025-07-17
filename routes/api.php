<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\VenteController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeController;

// Routes pour les employés
Route::get('employes', [EmployeController::class, 'index']); // Récupérer tous les employés
Route::post('employes', [EmployeController::class, 'store']); // Ajouter un employé
Route::put('employes/{id}', [EmployeController::class, 'update']); // Modifier un employé
Route::delete('employes/{id}', [EmployeController::class, 'destroy']); // Supprimer un employé

// Routes API pour les produits
Route::prefix('produits')->group(function () {
    Route::get('/', [ProduitController::class, 'index']);      // Tous les produits
    Route::post('/', [ProduitController::class, 'store']);     // Ajouter un produit
    Route::put('{id}', [ProduitController::class, 'update']);  // Modifier un produit
    Route::delete('{id}', [ProduitController::class, 'destroy']); // Supprimer un produit
    Route::get('{id}', [ProduitController::class, 'show']); // Détails produit
});

// Routes API pour les clients
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

// Routes API pour les catégories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategorieController::class, 'index']);  // Récupérer toutes les catégories
    Route::post('/', [CategorieController::class, 'store']); // Ajouter une catégorie
});

// Route de connexion pour les utilisateurs
Route::post('/login', [AuthController::class, 'login']); // Route pour se connecter
