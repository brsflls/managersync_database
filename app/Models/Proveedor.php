<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'cedula_juridica',
        'empresa_id',
    ];
    public function empresa()
{
    return $this->hasOne(Empresa::class);
}

}
