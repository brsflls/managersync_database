<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'facturas_compras';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'condicion_venta',
        'moneda',
        'plazo',
        'tipo_cambio',
        'observacion',
        'tipo_compra',
        'identificacion',
        'tipo_identificacion',
        'nombre',
        'telefono',
        'correo_electronico',
        'direccion_exacta',
        'provincia',
        'canton',
        'distrito',
        'barrio',
        'sub_total',
        'impuestos',
        'descuentos',
        'total',
        'archivo',
        'numero_exoneracion',
        'fecha_emision_exoneracion',
        'tipo_exoneracion',
        'porcentaje_exoneracion',
        'nombre_institucion_exoneracion'
    ];

    // Relación con los productos/servicios
    public function productosServicios()
    {
        return $this->hasMany(FacturaProductoServicio::class, 'factura_id');
    }

    // Relación con las referencias
    public function referencias()
    {
        return $this->hasMany(FacturaReferencia::class, 'factura_id');
    }
}
