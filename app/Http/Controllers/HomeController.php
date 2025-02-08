<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $testimonials = Testimonial::all();

        // Récupérer les produits les plus achetés des 20 dernières commandes
        $popularProducts = Product::withCount(['orderDetails as purchase_count'])
            ->whereHas('orderDetails.order', function ($query) {
                $query->latest()->take(20);
            })
            ->orderByDesc('purchase_count')
            ->take(8)
            ->get();

        return view('home', compact('categories', 'popularProducts', 'testimonials'));
    }
}
