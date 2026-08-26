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
        'current_checks',
        'total_checks',
        'current_cycle',
        'status',
    ];

    protected $casts = [
        'current_checks' => 'integer',
        'total_checks' => 'integer',
        'current_cycle' => 'integer',
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
