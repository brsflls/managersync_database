<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    /**
     * Obtiene todas las facturas con sus relaciones.
     */
    public function index()
    {
        return response()->json(Factura::with(['cliente', 'proveedor', 'usuario', 'detalles'])->get(), 200);
    }

    /**
     * Almacena una nueva factura.
     */
    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'usuario_id' => 'required|exists:usuarios,id',
          //  'numero_factura' => 'required|string|unique:faturas,numero_factura',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'total' => 'required|numeric',
            'tipo' => 'required|in:venta,compra',
            'estado' => 'in:Emitida,Pagada,Cancelada',
          //  'codigo_unico' => 'required|string|unique:faturas,codigo_unico',
            'xml_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear la factura
        $factura = Factura::create($request->all());
        return response()->json($factura, 201);
    }

    /**
     * Muestra una factura específica.
     */
    public function show($id)
    {
        $factura = Factura::with(['cliente', 'proveedor', 'usuario', 'detalles'])->find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        return response()->json($factura, 200);
    }

    /**
     * Actualiza una factura existente.
     */
    public function update(Request $request, $id)
    {
        $factura = Factura::find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'proveedor_id' => 'nullable|exists:proveedors,id',
            'usuario_id' => 'required|exists:usuarios,id',
            'numero_factura' => 'required|string|unique:faturas,numero_factura,' . $id,
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'total' => 'required|numeric',
            'tipo' => 'required|in:venta,compra',
            'estado' => 'in:Emitida,Pagada,Cancelada',
            'codigo_unico' => 'required|string|unique:faturas,codigo_unico,' . $id,
            'xml_data' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar la factura
        $factura->update($request->all());
        return response()->json($factura, 200);
    }

    /**
     * Elimina una factura existente.
     */
    public function destroy($id)
    {
        $factura = Factura::find($id);

        if (!$factura) {
            return response()->json(['message' => 'Factura no encontrada'], 404);
        }

        $factura->delete();
        return response()->json(['message' => 'Factura eliminada correctamente'], 204);
    }
}
