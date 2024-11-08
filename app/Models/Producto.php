<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo_producto', 
        'codigo_cabys', 
        'nombre', 
        'descripcion', 
        'precio_consumidor', 
        'precio_compra', 
        'stock', 
        'unidad_medida', 
        'peso_por_unidad', 
        'peso_neto', 
        'porcentaje_descuento', 
        'porcentaje_iva', 
        'monto_descuento', 
        'monto_iva', 
        'precio_neto', 
        'categoria',
        'empresa_id',
    ];
    public function empresa()
{
    return $this->belongsTo(Empresa::class);
}

}
