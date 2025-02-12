<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status','payment_method_id','address_id', 'coupon_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        // Restaurer le stock des produits
        foreach ($this->orderDetails as $detail) {
            $product = $detail->product;
            if ($product) {
                $product->increment('stock', $detail->quantity);
            }
        }

        // Modifier le statut de la commande
        $this->status = 'cancelled';
        $this->save();

        return true;
    }


    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }


    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function statusLabel()
    {
        return match ($this->status) {
            'pending' => '🟡 En attente',
            'processing' => '🔵 En préparation',
            'shipped' => '🚚 Expédiée',
            'delivered' => '✅ Livrée',
            'cancelled' => '❌ Annulée',
            default => '⏳ En attente',
        };
    }

    public function estimatedDelivery()
    {
        if ($this->status === 'shipped') {
            return $this->updated_at->addDays(3)->format('d/m/Y');
        }
        return null;
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }


}

