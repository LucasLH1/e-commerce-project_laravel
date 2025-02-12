<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        $search = $request->query('search');
        $categoriesFilter = $request->query('categories');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sortOrder = $request->query('sort'); // Récupération du tri

        $query = Product::with('images')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        if ($categoriesFilter) {
            $categoryIds = explode(',', $categoriesFilter);
            $query->whereIn('category_id', $categoryIds);
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($sortOrder === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sortOrder === 'desc') {
            $query->orderBy('price', 'desc');
        }

        $products = $query->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoriesFilter', 'minPrice', 'maxPrice', 'sortOrder'));
    }


    public function show($id)
    {
        $product = Product::with(['images', 'reviews.user'])->findOrFail($id);
        $user = auth()->user();

        $hasPurchased = false;
        if ($user) {
            $hasPurchased = $user->orders()
                ->whereHas('orderDetails', function ($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->exists();
        }

        $hasReviewed = false;
        if ($hasPurchased) {
            $hasReviewed = $user->reviews()->where('product_id', $id)->exists();
        }

        return view('products.show', compact('product', 'hasPurchased', 'hasReviewed'));
    }




}
