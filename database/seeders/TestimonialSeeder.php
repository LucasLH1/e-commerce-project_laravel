<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder {
    public function run() {
        Testimonial::create([
            'name' => 'Alice Dupont',
            'location' => 'Paris, France',
            'message' => 'Super site ! Livraison rapide et produits de qualité.',
            'image' => 'images/testimonials/alice.jpg'
        ]);

        Testimonial::create([
            'name' => 'Jean Martin',
            'location' => 'Lyon, France',
            'message' => 'Très satisfait de mon achat, je recommande !',
            'image' => 'images/testimonials/jean.jpg'
        ]);

        Testimonial::create([
            'name' => 'Sophie Bernard',
            'location' => 'Marseille, France',
            'message' => 'Une excellente expérience, service client au top !',
            'image' => 'images/testimonials/sophie.jpg'
        ]);
    }
}

