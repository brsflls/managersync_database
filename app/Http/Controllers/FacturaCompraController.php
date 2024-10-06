<?php

namespace App\Http\Controllers;

use App\Models\FacturaCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacturaCompraController extends Controller
{
    public function index()
    {
        $facturas = FacturaCompra::all();
        return response()->json($facturas, 200);
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'condicion_venta' => 'required|string',
            'moneda' => 'required|string',
            'plazo' => 'nullable|integer',
            'tipo_cambio' => 'nullable|numeric',
            'observacion' => 'nullable|string',
            'tipo_compra' => 'required|string',
            'identificacion' => 'required|string',
            'tipo_identificacion' => 'required|string',
            'nombre' => 'required|string',
            'telefono' => 'nullable|string',
            'correo_electronico' => 'nullable|email',
            'direccion_exacta' => 'nullable|string',
            'provincia' => 'nullable|string',
            'canton' => 'nullable|string',
            'distrito' => 'nullable|string',
            'barrio' => 'nullable|string',
            'sub_total' => 'required|numeric',
            'impuestos' => 'nullable|numeric',
            'descuentos' => 'nullable|numeric',
            'total' => 'required|numeric',
            'archivo' => 'nullable|string',
            'numero_exoneracion' => 'nullable|string',
            'fecha_emision_exoneracion' => 'nullable|date',
            'tipo_exoneracion' => 'nullable|string',
            'porcentaje_exoneracion' => 'nullable|numeric',
            'nombre_institucion_exoneracion' => 'nullable|string',
            'productosServicios' => 'required|array',
            'productosServicios.*.codigo' => 'required|string',
            'productosServicios.*.descripcion' => 'required|string',
            'productosServicios.*.cantidad' => 'required|numeric|min:1',
            'productosServicios.*.precio_bruto' => 'required|numeric',
            'productosServicios.*.porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'productosServicios.*.porcentaje_iva' => 'nullable|numeric|min:0|max:100',
            'productosServicios.*.servicio' => 'required|boolean',
            'referencias' => 'nullable|array',
            'referencias.*.tipo_documento' => 'required|string',
            'referencias.*.numero_documento' => 'required|string',
            'referencias.*.fecha_documento' => 'required|date',
            'referencias.*.tipo_referencia' => 'required|string',
        ]);

        // Usar una transacción para asegurarnos de que todo se guarde correctamente
        DB::beginTransaction();

        try {
            // Crear la factura principal
            $factura = FacturaCompra::create($request->only([
                'condicion_venta', 'moneda', 'plazo', 'tipo_cambio', 'observacion', 'tipo_compra',
                'identificacion', 'tipo_identificacion', 'nombre', 'telefono', 'correo_electronico',
                'direccion_exacta', 'provincia', 'canton', 'distrito', 'barrio',
                'sub_total', 'impuestos', 'descuentos', 'total', 'archivo',
                'numero_exoneracion', 'fecha_emision_exoneracion', 'tipo_exoneracion',
                'porcentaje_exoneracion', 'nombre_institucion_exoneracion'
            ]));

            // Agregar los productos/servicios a la tabla relacionada
            if ($request->has('productosServicios')) {
                foreach ($request->productosServicios as $producto) {
                    $factura->productosServicios()->create([
                        'codigo' => $producto['codigo'],
                        'descripcion' => $producto['descripcion'],
                        'cantidad' => $producto['cantidad'],
                        'precio_bruto' => $producto['precio_bruto'],
                        'porcentaje_descuento' => $producto['porcentaje_descuento'],
                        'porcentaje_iva' => $producto['porcentaje_iva'],
                        'servicio' => $producto['servicio'],
                    ]);
                }
            }

            // Agregar las referencias a la tabla relacionada
            if ($request->has('referencias')) {
                foreach ($request->referencias as $referencia) {
                    $factura->referencias()->create([
                        'tipo_documento' => $referencia['tipo_documento'],
                        'numero_documento' => $referencia['numero_documento'],
                        'fecha_documento' => $referencia['fecha_documento'],
                        'tipo_referencia' => $referencia['tipo_referencia'],
                    ]);
                }
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Factura creada con éxito', 'factura' => $factura], 201);
        } catch (\Exception $e) {
            // Deshacer la transacción si hay un error
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la factura', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $factura = FacturaCompra::find($id);
        return response()->json($factura, 200);
    }

    public function update(Request $request, $id)
    {
        $factura = FacturaCompra::findOrFail($id);
        $factura->update($request->all());
        return response()->json($factura, 200);
    }

    public function destroy($id)
    {
        FacturaCompra::destroy($id);
        return response()->json(['message' => 'Factura eliminada'], 200);
    }
}
