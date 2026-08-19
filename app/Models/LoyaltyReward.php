<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyReward extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_USED = 'used';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'card_id',
        'reward_catalog_id',
        'earned_from_movement_id',
        'cycle_number',
        'milestone',
        'reward_name',
        'reward_description',
        'status',
        'earned_at',
        'redeemed_at',
        'redeemed_by',
    ];

    protected $casts = [
        'cycle_number' => 'integer',
        'milestone' => 'integer',
        'earned_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    public function card()
    {
        return $this->belongsTo(LoyaltyCard::class, 'card_id');
    }

    public function catalog()
    {
        return $this->belongsTo(LoyaltyRewardCatalog::class, 'reward_catalog_id');
    }

    public function earnedFromMovement()
    {
        return $this->belongsTo(LoyaltyMovement::class, 'earned_from_movement_id');
    }

    public function redeemedBy()
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }
}
