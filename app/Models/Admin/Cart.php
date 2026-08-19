<?php

namespace App\Models\Admin;

use App\Models\LoyaltyMovement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $primaryKey = 'id_cart';
    protected $fillable = ['subcategory_id', 'coupon_id', 'description', 'date_reserved', 'hour_init', 'quantity_lane', 'quantity_hours', 'quantity_guests', 'payment_type', 'amount_discount', 'amount', 'document_type', 'rsocial', 'ruc', 'dir', 'observation_client', 'status','invoice', 'client_id'];

    public function subcategory()
    {
        return $this->belongsTo(SubCategories::class, 'subcategory_id');
    }

    public function loyaltyMovement()
    {
        return $this->hasOne(LoyaltyMovement::class, 'cart_id', 'id_cart');
    }

    /**
     * Obtiene todas las reservas de un cliente de forma estática.
     *
     * @param  int  $clientId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    static public function getReservationsByClient($clientId)
    {
        return self::where('client_id', $clientId)
            ->with('subcategory:img_subcategory,id_subcategory')
            ->orderByDesc('id_cart')
            ->get();
    }

    /**
     * Cuenta las reservas de un cliente.
     *
     * @param  int  $clientId
     * @return int
     */
    static public function countReservationsByClient($clientId)
    {
        return self::where('client_id', $clientId)->count();
    }
}
