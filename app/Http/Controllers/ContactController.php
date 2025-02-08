<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Simule l'envoi (à remplacer par un vrai envoi d'email si besoin)
        return redirect()->back()->with('success', 'Votre message a bien été envoyé !');
    }
}
