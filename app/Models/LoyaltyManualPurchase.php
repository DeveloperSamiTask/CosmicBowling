<?php

namespace App\Models;

use App\Models\Frontend\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyManualPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'receipt_number',
        'total_hours',
        'amount',
        'purchased_at',
        'registered_by',
        'notes',
    ];

    protected $casts = [
        'total_hours' => 'integer',
        'amount' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function movement()
    {
        return $this->hasOne(LoyaltyMovement::class, 'manual_purchase_id');
    }
}
