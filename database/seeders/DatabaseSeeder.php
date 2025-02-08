<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Création des rôles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);

        // Création d'un admin unique
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Création de 5 managers
        User::factory(5)->create()->each(function ($user) use ($managerRole) {
            $user->assignRole($managerRole);
        });

        // Création de 20 clients
        User::factory(20)->create()->each(function ($user) use ($clientRole) {
            $user->assignRole($clientRole);
        });

        // Création des catégories
        Category::factory(6)->create();

        // Création des produits
        Product::factory(30)->create();
    }
}
