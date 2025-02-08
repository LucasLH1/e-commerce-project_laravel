<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $user = auth()->user();

        // Vérifie si l'utilisateur a bien commandé le produit
        $hasPurchased = $user->orders()
            ->whereHas('orderDetails', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Vous devez acheter ce produit pour laisser un avis.');
        }

        // Vérifie si un avis existe déjà
        $existingReview = Review::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà laissé un avis pour ce produit.');
        }

        // Création de l'avis
        Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }
}
