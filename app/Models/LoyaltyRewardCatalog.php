<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyRewardCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'milestone' => 'integer',
        'active' => 'boolean',
    ];

    public function rewards()
    {
        return $this->hasMany(LoyaltyReward::class, 'reward_catalog_id');
    }
}
