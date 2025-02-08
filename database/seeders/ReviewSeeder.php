<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        DB::table('reviews')->truncate(); // Supprime tous les avis pour éviter les conflits

        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️ Aucun utilisateur ou produit trouvé. Ajoutez des utilisateurs et des produits avant de lancer ce seeder.');
            return;
        }

        foreach ($products as $product) {
            for ($i = 0; $i < rand(3, 10); $i++) {
                Review::create([
                    'user_id' => $users->random()->id,
                    'product_id' => $product->id,
                    'rating' => rand(1, 5),
                    'comment' => 'Avis généré automatiquement.'
                ]);
            }
        }

        $this->command->info('✔️ Avis générés avec succès.');
    }
}


