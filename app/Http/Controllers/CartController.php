<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = session()->get('cart', []);

        foreach ($cartItems as $key => $item) {
            if (!isset($item['id'])) {
                \Log::error("Produit sans ID dans le panier", ['item' => $item]);
            }
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems));

        session()->put('cart_total', $total); // Remet le total normal
        session()->forget('applied_coupon'); // Supprime le coupon actif s'il y en avait un

        return view('cart.index', compact('cartItems', 'total'));
    }


    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function update(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = max(1, intval($request->quantity));
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function add(Request $request, Product $product)
    {
        try {
            Log::info('Ajout au panier : Produit ID ' . $product->id);

            if (!$product) {
                Log::error('Erreur : Produit introuvable ID ' . $product->id);
                return response()->json(['success' => false, 'message' => 'Produit introuvable'], 404);
            }

            $cart = session()->get('cart', []);

            if (!is_array($cart)) {
                Log::warning("Le panier était corrompu. Réinitialisation...");
                $cart = [];
            }

            foreach ($cart as $key => $value) {
                if (!is_array($value)) {
                    Log::warning("Valeur corrompue détectée dans le panier pour l'ID $key. Suppression...");
                    unset($cart[$key]);
                }
            }

            if (!isset($cart[$product->id]) || !is_array($cart[$product->id])) {
                Log::info("Produit ID {$product->id} non trouvé dans le panier. Initialisation...");
                $cart[$product->id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->images->first()->image_path ?? '/images/default.jpg',
                    'quantity' => 0,
                ];
            }

            if (!isset($cart[$product->id]['quantity']) || !is_numeric($cart[$product->id]['quantity'])) {
                Log::warning("Quantité corrompue détectée pour le produit ID {$product->id}. Réinitialisation...");
                $cart[$product->id]['quantity'] = 0;
            }

            $cart[$product->id]['quantity'] += 1;

            // Sauvegarder en session
            session()->put('cart', $cart);
            Log::info("Produit ID {$product->id} ajouté au panier avec succès. Quantité : " . $cart[$product->id]['quantity']);

            return response()->json(['success' => true, 'cart' => $cart]);

        } catch (\Exception $e) {
            Log::error("Exception : " . $e->getMessage(), [
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'success' => false,
                'message' => "Erreur interne : " . $e->getMessage(),
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $couponCode = $request->input('code');
        Log::info("Tentative d'application du coupon: $couponCode");

        $coupon = Coupon::where('code', $couponCode)
            ->where('is_active', 1)
            ->where('expiration_date', '>', now())
            ->first();

        if (!$coupon) {
            Log::error("Coupon invalide ou expiré: $couponCode");
            return response()->json(['success' => false]);
        }

        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($coupon->discount_type === 'fixed') {
            $newTotal = max(0, $total - $coupon->discount_value);
        } elseif ($coupon->discount_type === 'percentage') {
            $discountAmount = ($coupon->discount_value / 100) * $total;
            $newTotal = max(0, $total - $discountAmount);
        } else {
            Log::error("Type de réduction inconnu pour le coupon: $couponCode");
            return response()->json(['success' => false, 'message' => "Erreur lors de l'application du coupon."]);
        }
        //Log::info("Coupon appliqué: $couponCode - Réduction: $discount - Ancien total: $total - Nouveau total: $newTotal");

        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value
        ]);
        session()->put('cart_total', $newTotal);

        return response()->json([
            'success' => true,
            'old_total' => $total,
            'new_total' => $newTotal
        ]);
    }


}


