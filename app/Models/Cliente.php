<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Especifica los campos que son masivos asignables
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'cedula'
    ];
}
