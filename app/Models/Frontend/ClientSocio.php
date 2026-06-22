<?php

namespace App\Models\Frontend; // <--- ESTO ES LO QUE IMPORTA
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Frontend\Client;

class ClientSocio extends Model
{
    use HasFactory;

    protected $table = 'client_socio';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'client_id',
        'nTarjNumb',
        'cTarjActi',
        'dEmisDate',
        'dCaduDate',
        'validado',
        'affiliation',
        'status_magic',
        'user_new',
        'user_renew',

        // 'apod_nombre',   // nombre completo del apoderado
        // 'apod_doc',      // DNI del apoderado
        'phone_number',
        'confirmation_email',
        'validado'
    ];

    // Relación con Client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }

    // Acceso directo al tipo de doc a través de client
    public function getTipoDocAttribute()
    {
        return optional($this->client)->sunatTypedoc;
    }

    public function proxy()
    {
        return $this->belongsTo(Proxy::class, 'proxy_id', 'proxy_id');
    }
}
