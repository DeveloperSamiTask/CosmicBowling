<?php

namespace App\Models\Frontend;

use App\Models\Admin\SunatTypeDoc;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Client extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'id_client';
    protected $fillable = [
        'document_id',
        'number_doc',
        'lastname_pat',
        'lastname_mat',
        'names_client',
        'email_client',
        'phone_client',
        'birthday_client',
        'address_client',
        'password_client',

        //nuevos campos : borrar validacion
        'validacion',
        'socio',


    ];

    protected $hidden = [
        'password_client',
        'remember_token'
    ];

    // Cupones impresos por este cliente
    public function cupones()
    {
        return $this->hasMany(PrintCupon::class, 'client_id', 'id_client');
    }

    public function partner()
    {
        // Un cliente tiene un registro de socio
        return $this->hasOne(ClientSocio::class, 'client_id', 'id_client');
    }

    // public function proxy() {
    //     return $this->belongsTo(Proxy::class, 'proxy_id', 'proxy_id');
    // }

    public function sunatTypedoc()
    {
        return $this->belongsTo(SunatTypeDoc::class, 'document_id');
    }

    public function getSunatDocAttribute()
    {
        return $this->sunatTypedoc ? $this->sunatTypedoc->id_doc : null;
    }

    public function findForPassport($username)
    {
        return $this->where('number_doc', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password_client);
    }




    static public function getClients()
    {
        $clients = self::all();
        $data = [];

        foreach ($clients as $client) {
            $data[] = [
                'id' => $client->id_client,
                'names' => trim("{$client->lastname_pat} {$client->lastname_mat} {$client->names_client}"),
                'type_doc' => optional($client->sunatTypedoc)->name_doc,
                'number_doc' => $client->number_doc,
                'email' => $client->email_client,
                'phone' => $client->phone_client,
                'address' => $client->address_client,
                'verified' => $client->email_verified_at,
                'status' => $client->email_verified_at ? 'Validado' : 'No Validado'
            ];
        }

        return $data;
    }
}
