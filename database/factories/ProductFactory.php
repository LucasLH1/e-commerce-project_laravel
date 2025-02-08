<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Laptop HP Pavilion', 'MacBook Pro 14"', 'Souris Logitech MX Master',
                'Clavier mécanique Corsair', 'Disque SSD Samsung 1TB', 'Webcam Logitech C920',
                'Imprimante Epson EcoTank', 'Adaptateur USB-C vers HDMI', 'Carte Graphique NVIDIA RTX 3070'
            ]),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 50, 2000),
            'stock' => $this->faker->numberBetween(1, 100),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1, // Assure qu'une catégorie existe
            'image' => 'https://via.placeholder.com/150', // Image fictive
        ];
    }
}

