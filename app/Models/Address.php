<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'street', 'city', 'postal_code', 'country', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
