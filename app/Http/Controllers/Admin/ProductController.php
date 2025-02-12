<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048'
            ]);


            $product = Product::create($request->only(['name', 'description', 'price', 'stock', 'category_id']));

            if ($request->hasFile('images')) {
                $index = 1;
                foreach ($request->file('images') as $image) {

                    $productName = strtolower(str_replace(' ', '_', $product->name));
                    $extension = $image->getClientOriginalExtension();

                    $imageName = "{$productName}_{$index}.{$extension}";
                    $imageFolder = public_path("images/products/{$productName}");

                    if (!file_exists($imageFolder)) {
                        mkdir($imageFolder, 0777, true);
                    }

                    $imagePath = "images/products/{$productName}/{$imageName}";
                    $image->move($imageFolder, $imageName);

                    $product->images()->create(['image_path' => $imagePath]);

                    $index++;
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Produit ajouté avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'description', 'price', 'stock', 'category_id']));

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                $oldImagePath = public_path($image->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $image->delete();
            }

            $index = 1;
            foreach ($request->file('images') as $image) {
                $productName = strtolower(str_replace(' ', '_', $product->name));
                $extension = $image->getClientOriginalExtension();

                $imageName = "{$productName}_{$index}.{$extension}";
                $imagePath = "images/products/{$productName}/{$imageName}";

                $image->move(public_path("images/products/{$productName}"), $imageName);

                $product->images()->create(['image_path' => $imagePath]);

                $index++;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->images()->exists()) {
            foreach ($product->images as $image) {
                $imagePath = public_path($image->image_path);

                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $productFolder = public_path("images/products/" . strtolower(str_replace(' ', '_', $product->name)));
            if (is_dir($productFolder) && count(scandir($productFolder)) == 2) {
                rmdir($productFolder);
            }

            $product->images()->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès.');
    }

}
