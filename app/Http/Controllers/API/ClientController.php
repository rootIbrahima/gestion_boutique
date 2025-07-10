<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    // 🟢 Lister tous les clients
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    // 🟢 Ajouter un client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        try {
            $client = Client::create([
                'nom' => $validated['nom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'points_fidelite' => 0,
            ]);

            return response()->json([
                'message' => 'Client ajouté avec succès!',
                'client' => $client
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de l\'ajout du client',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    // 🟢 Afficher les détails d'un client
    public function show($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'error' => 'Client non trouvé'
            ], 404);
        }

        return response()->json($client);
    }

    // 🟢 Modifier un client
    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['error' => 'Client non trouvé'], 404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'telephone' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Client mis à jour avec succès',
            'client' => $client
        ]);
    }

    // 🗑️ Supprimer un client
    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['error' => 'Client non trouvé'], 404);
        }

        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès']);
    }
}
