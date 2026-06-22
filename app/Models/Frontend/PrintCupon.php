<?php

namespace App\Models\Frontend;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintCupon extends Model
{
    use HasFactory;

    protected $table = 'print_cupon';
    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'cupon_id',
        'fecha_print',
        'estado',

        'mes',
        'anio',
    ];

    // Relación hacia Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }
}
