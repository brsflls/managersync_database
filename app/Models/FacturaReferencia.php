<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaReferencia extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'factura_referencias';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'factura_id',
        'tipo_documento',
        'numero_documento',
        'fecha_documento',
        'tipo_referencia',
    ];

    // Relación inversa: una referencia pertenece a una factura
    public function factura()
    {
        return $this->belongsTo(FacturaCompra::class, 'factura_id');
    }
}
