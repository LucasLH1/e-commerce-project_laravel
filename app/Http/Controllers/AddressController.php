<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class AddressController extends Controller
{
    /**
     * Afficher la liste des adresses utilisateur.
     */
    public function index()
    {
        return view('profile.addresses', ['addresses' => Auth::user()->addresses]);
    }

    /**
     * Afficher le formulaire d'ajout d'une adresse.
     */
    public function create()
    {
        return view('profile.address-form');
    }

    /**
     * Enregistrer une nouvelle adresse.
     */
    public function store(Request $request)
    {
        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'is_active' => 'sometimes|boolean',
        ]);

        // Créer l'adresse
        $address = Auth::user()->addresses()->create([
            'street' => $request->street,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'is_active' => $request->filled('is_active'),
        ]);

        // Si l'adresse est définie comme principale, on désactive les autres
        if ($address->is_active) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_active' => false]);
        }

        return response()->json([
            'message' => 'Adresse ajoutée avec succès !',
            'address' => $address
        ], 201);
    }


    /**
     * Afficher le formulaire de modification d'une adresse.
     */
    public function edit($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        return view('profile.address-form', compact('address'));
    }

    /**
     * Mettre à jour une adresse.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        $address = Auth::user()->addresses()->findOrFail($id);
        $address->update($request->only(['street', 'city', 'country']));

        return redirect()->route('profile.index')->with('success', 'Adresse mise à jour avec succès !');
    }

    /**
     * Supprimer une adresse.
     */
    public function destroy($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return redirect()->route('profile.index')->with('success', 'Adresse supprimée avec succès !');
    }

    /**
     * Définir une adresse comme principale.
     */
    public function setActive($id)
    {
        $user = Auth::user();

        // Désactiver toutes les autres adresses
        $user->addresses()->update(['is_active' => false]);

        // Activer l'adresse sélectionnée
        $address = $user->addresses()->findOrFail($id);
        $address->update(['is_active' => true]);

        return response()->json(['success' => true, 'message' => 'Adresse principale mise à jour !']);
    }

}
