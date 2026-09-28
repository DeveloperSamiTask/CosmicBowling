<?php

namespace App\Models;

use App\Models\Frontend\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    protected $fillable = [
        'client_id',
        'card_number',
        'issued_at',
        'expires_at',
        'last_expired_at',
        'current_checks',
        'total_checks',
        'current_cycle',
        'status',
    ];

    protected $casts = [
        'current_checks' => 'integer',
        'total_checks' => 'integer',
        'current_cycle' => 'integer',
        'issued_at' => 'date',
        'expires_at' => 'date',
        'last_expired_at' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }

    public function movements()
    {
        return $this->hasMany(LoyaltyMovement::class, 'card_id');
    }

    public function rewards()
    {
        return $this->hasMany(LoyaltyReward::class, 'card_id');
    }
}
