<?php

namespace App\Models\Frontend; // <--- ESTO ES LO QUE IMPORTA

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proxy extends Model
{
  use HasFactory;

    protected $table = 'apoderado_cliente';
    protected $primaryKey = 'proxy_id';
    public $timestamps = false;
    protected $fillable = [
        'proxy_client', // <--- No olvides este para poder guardarlo
        'proxy_pattername',
        'proxy_mattername',
        'proxy_names',
        'proxy_doc'
    ];



    public function socios()
    {
        return $this->hasMany(ClientSocio::class, 'proxy_id', 'proxy_id');
    }
}
