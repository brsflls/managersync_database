<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'proveedor_id',
        'usuario_id',
        'numero_factura',
        'fecha_emision',
        'fecha_vencimiento',
        'total',
        'tipo',
        'estado',
        'codigo_unico',
        'xml_data',
    ];

    // Relación con cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    // Relación con detalles de factura
    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class);
    }
}
