<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_producto', 
        'codigo_cabys', 
        'nombre',
        'descripcion',
        'precio_compra',
        'precio_consumidor',
        'stock',
        'unidad_medida',
        'porcentaje_descuento',
        'monto_descuento',
        'porcentaje_iva',
        'monto_iva',
        'precio_neto',
        'peso_por_unidad',
        'peso_neto',
    ];
}
