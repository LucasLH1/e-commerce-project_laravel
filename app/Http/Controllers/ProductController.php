<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des catégories pour le filtrage
        $categories = Category::all();

        // Récupération des paramètres de filtre
        $search = $request->query('search');
        $categoriesFilter = $request->query('categories');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sortOrder = $request->query('sort'); // Récupération du tri

        // Requête de base pour récupérer les produits avec leurs images
        $query = Product::with('images')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filtrage par nom
        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        // Filtrage par catégories multiples
        if ($categoriesFilter) {
            $categoryIds = explode(',', $categoriesFilter);
            $query->whereIn('category_id', $categoryIds);
        }

        // Filtrage par prix
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Ajout du tri par prix
        if ($sortOrder === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortOrder === 'desc') {
            $query->orderBy('price', 'desc');
        }

        // Récupération des produits sans pagination
        $products = $query->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoriesFilter', 'minPrice', 'maxPrice', 'sortOrder'));
    }


    public function show($id)
    {
        $product = Product::with(['images', 'reviews.user'])->findOrFail($id);
        $user = auth()->user();

        // Vérifie si l'utilisateur a commandé ce produit
        $hasPurchased = false;
        if ($user) {
            $hasPurchased = $user->orders()
                ->whereHas('orderDetails', function ($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->exists();
        }

        // Vérifie si l'utilisateur a déjà laissé un avis pour ce produit
        $hasReviewed = false;
        if ($hasPurchased) {
            $hasReviewed = $user->reviews()->where('product_id', $id)->exists();
        }

        return view('products.show', compact('product', 'hasPurchased', 'hasReviewed'));
    }




}
