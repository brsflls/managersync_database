<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaProductoServicio extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'factura_productos_servicios';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'factura_id',
        'codigo',
        'descripcion',
        'cantidad',
        'precio_bruto',
        'porcentaje_descuento',
        'porcentaje_iva',
        'servicio',
    ];

    // Relación inversa: un producto/servicio pertenece a una factura
    public function factura()
    {
        return $this->belongsTo(FacturaCompra::class, 'factura_id');
    }
}
