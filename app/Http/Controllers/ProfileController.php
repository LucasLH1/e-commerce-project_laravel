<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Afficher la page du profil utilisateur.
     */
    public function index()
    {
        return view('profile.index', ['user' => Auth::user()]);
    }

    /**
     * Mettre à jour les informations utilisateur.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès !');
    }
}
