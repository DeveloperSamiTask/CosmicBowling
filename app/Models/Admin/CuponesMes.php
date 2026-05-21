<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// El nombre de la clase DEBE ser idéntico al nombre del archivo CuponesMes.php
class CuponesMes extends Model
{
    use HasFactory;

    // Aquí le dices a Laravel que busque la tabla 'cupones_mes' en la BD
    protected $table = 'cupones_mes';

    protected $fillable = ['mes', 'anio', 'cupon1', 'cupon2','subcategory_id' ,'path_cupon'];
}
