<?php

namespace App\Models;

use App\Models\Admin\Cart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyMovement extends Model
{
    use HasFactory;

    public const TYPE_WEB_PURCHASE = 'web_purchase';
    public const TYPE_MANUAL_PURCHASE = 'manual_purchase';
    public const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'card_id',
        'cart_id',
        'manual_purchase_id',
        'movement_type',
        'checks',
        'checks_before',
        'checks_after',
        'cycle_before',
        'cycle_after',
        'description',
        'registered_by',
    ];

    protected $casts = [
        'checks' => 'integer',
        'checks_before' => 'integer',
        'checks_after' => 'integer',
        'cycle_before' => 'integer',
        'cycle_after' => 'integer',
    ];

    public function card()
    {
        return $this->belongsTo(LoyaltyCard::class, 'card_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id_cart');
    }

    public function manualPurchase()
    {
        return $this->belongsTo(LoyaltyManualPurchase::class, 'manual_purchase_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function earnedRewards()
    {
        return $this->hasMany(LoyaltyReward::class, 'earned_from_movement_id');
    }
}
