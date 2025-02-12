<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions'; // Spécifie la table des sessions

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'
    ];

    public $timestamps = false;
}
