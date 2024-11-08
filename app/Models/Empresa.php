<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    // Define el nombre de la tabla (opcional si sigue la convención de nombres)
    protected $table = 'empresas';

    // Define los campos que son asignables en masa
    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
        'codigo_actividad',
       'descripcion',
        'cedula_empresa',
        'provincia',
        'canton',
        'distrito',
        'otras_senas',
        'empresa',
       
       
    ];

    // Define las relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
}
