<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    // Récupérer tous les employés
    public function index()
    {
        return Employe::all();
    }

    // Ajouter un employé
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:employes,email',
            'poste' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20', // Téléphone est optionnel
        ]);

        $employe = Employe::create($validated);

        return response()->json($employe, 201);
    }

    // Mettre à jour un employé
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email',
            'poste' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
        ]);

        $employe = Employe::findOrFail($id);
        $employe->update($validated);

        return response()->json($employe);
    }

    // Supprimer un employé
    public function destroy($id)
    {
        $employe = Employe::findOrFail($id);
        $employe->delete();

        return response()->json(null, 204);
    }
}
