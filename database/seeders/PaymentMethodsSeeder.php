<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run()
    {
        DB::table('payment_methods')->insert([
            ['name' => 'Carte Bancaire', 'slug' => 'card', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PayPal', 'slug' => 'paypal', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Apple Pay', 'slug' => 'apple_pay', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

