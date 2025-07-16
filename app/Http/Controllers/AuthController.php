<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login pour générer un token API.
     */
    public function login(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Vérification des informations d'identification
        if (Auth::attempt($validated)) {
            $user = Auth::user();
            // Créer un token pour l'utilisateur
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        // Si l'authentification échoue
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }
}
