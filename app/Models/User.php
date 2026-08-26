<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function tableKardex()
    {
        $kardex = self::all();

        $data = [];

        foreach ($kardex as $row) {
            $data[] = [
                'id' => $row->id,
                'name' => $row->name,
                'username' => $row->username,
                'email' => $row->email,
            ];
        }
        return $data;
    }

    public function registeredLoyaltyPurchases()
    {
        return $this->hasMany(LoyaltyManualPurchase::class, 'registered_by');
    }

    public function registeredLoyaltyMovements()
    {
        return $this->hasMany(LoyaltyMovement::class, 'registered_by');
    }

    public function redeemedLoyaltyRewards()
    {
        return $this->hasMany(LoyaltyReward::class, 'redeemed_by');
    }
}
