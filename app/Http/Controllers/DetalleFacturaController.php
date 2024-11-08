<?php

namespace App\Http\Controllers;

use App\Models\DetalleFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetalleFacturaController extends Controller
{
    /**
     * Obtiene todos los detalles de facturas con sus relaciones.
     */
    public function index()
    {
        return response()->json(DetalleFactura::with(['factura', 'producto'])->get(), 200);
    }

    /**
     * Almacena un nuevo detalle de factura.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            //'factura_id' => 'required|exists:facturas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric',
            'total' => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Crear el detalle de factura
        $detalleFactura = DetalleFactura::create($request->all());
        return response()->json($detalleFactura, 201);
    }
    

    /**
     * Muestra un detalle de factura específico.
     */
    public function show($id)
    {
        $detalleFactura = DetalleFactura::with(['factura', 'producto'])->find($id);

        if (!$detalleFactura) {
            return response()->json(['message' => 'Detalle de factura no encontrado'], 404);
        }

        return response()->json($detalleFactura, 200);
    }

    /**
     * Actualiza un detalle de factura existente.
     */
    public function update(Request $request, $id)
    {
        $detalleFactura = DetalleFactura::find($id);

        if (!$detalleFactura) {
            return response()->json(['message' => 'Detalle de factura no encontrado'], 404);
        }

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'factura_id' => 'required|exists:faturas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric',
            'total' => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar el detalle de factura
        $detalleFactura->update($request->all());
        return response()->json($detalleFactura, 200);
    }

    /**
     * Elimina un detalle de factura existente.
     */
    public function destroy($id)
    {
        $detalleFactura = DetalleFactura::find($id);

        if (!$detalleFactura) {
            return response()->json(['message' => 'Detalle de factura no encontrado'], 404);
        }

        $detalleFactura->delete();
        return response()->json(['message' => 'Detalle de factura eliminado correctamente'], 204);
    }
}
