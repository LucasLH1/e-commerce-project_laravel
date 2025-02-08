<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'discount_type', 'discount_value', 'min_order_amount', 'expiration_date', 'is_active'];

    public static function applyCoupon($code, $total)
    {
        $coupon = self::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return ['error' => 'Code promo invalide'];
        }

        if ($coupon->expiration_date && now()->gt($coupon->expiration_date)) {
            return ['error' => 'Ce code promo est expiré'];
        }

        if ($coupon->min_order_amount && $total < $coupon->min_order_amount) {
            return ['error' => 'Le montant minimum pour utiliser ce code promo n’est pas atteint'];
        }

        $discount = ($coupon->discount_type === 'percentage')
            ? ($total * $coupon->discount_value / 100)
            : $coupon->discount_value;

        return ['success' => true, 'discount' => $discount];
    }
}

