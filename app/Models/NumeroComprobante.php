<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroComprobante extends Model
{
    use HasFactory;

    protected $table = 'numeros_comprobante';

    protected $fillable = ['ultimo_numero'];

    public static function obtenerSiguienteNumero()
    {
        $numeroComprobante = self::first();

        // Si no existe, crea el primer registro
        if (!$numeroComprobante) {
            $numeroComprobante = self::create(['ultimo_numero' => 1]);
        }

        // Incrementa el último número y lo actualiza en la base de datos
        $numeroActual = $numeroComprobante->ultimo_numero;
        $nuevoNumero = $numeroActual + 1;
        $numeroComprobante->update(['ultimo_numero' => $nuevoNumero]);

        return $numeroActual; // Devuelve el número anterior antes de incrementar
    }
}
