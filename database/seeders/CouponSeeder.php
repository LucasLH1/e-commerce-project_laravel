<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::insert([
            [
                'code' => 'PROMO10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_amount' => 50,
                'expiration_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'REDUCTION5',
                'discount_type' => 'fixed',
                'discount_value' => 5,
                'min_order_amount' => 20,
                'expiration_date' => Carbon::now()->addDays(15),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BLACKFRIDAY',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_order_amount' => 100,
                'expiration_date' => Carbon::now()->addDays(10),
                'is_active' => false, // Désactivé
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
