<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el empresa_id del request si está presente
        $empresaId = $request->query('empresa_id');
        
        // Filtrar productos por empresa_id si está presente, o traer todos si no se especifica
        $productos = $empresaId 
            ? Producto::where('empresa_id', $empresaId)->get() 
            : Producto::all();
        
        \Log::info('Productos: ', $productos->toArray());
        return response()->json($productos, 200);
    }
    

    public function store(Request $request)
{
    try {
        \Log::info('Datos recibidos:', $request->all());
        
        $validated = $request->validate([
            'codigo_producto' => 'required|string',
            'codigo_cabys' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_consumidor' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad_medida' => 'required|string',
            'peso_por_unidad' => 'required|numeric|min:0',
           'porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'porcentaje_iva' => 'required|numeric|min:0|max:100',
            'categoria' => 'required|string|max:255',
        ]);

        // Cálculos adicionales
        $pesoNeto = $validated['peso_por_unidad'] * $validated['stock'];
        $monto_descuento = ($validated['precio_consumidor'] * $validated['porcentaje_descuento']) / 100;
        $monto_iva = (($validated['precio_consumidor'] - $monto_descuento) * ($validated['porcentaje_iva'] / 100));

        $producto = Producto::create(array_merge($validated, [
            'peso_neto' => $pesoNeto,
            'monto_descuento' => $monto_descuento,
            'monto_iva' => $monto_iva,
            'precio_neto' => $validated['precio_consumidor'] - $monto_descuento + $monto_iva,
        ]));

        return response()->json(['success' => 'Producto creado con éxito.', 'producto' => $producto], 201);
    } catch (\Throwable $th) {
        \Log::error('Error al almacenar producto: ', ['error' => $th->getMessage()]);
        return response()->json(['error' => 'No se pudo crear el producto'], 400);
    }
}

    public function update(Request $request, string $id)
    {
        $request->validate([
            'codigo_producto' => 'required|string',
            'empresa_id' => 'required|exists:empresas,id',
            'codigo_cabys' => 'required|string',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_consumidor' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad_medida' => 'required|string',
            'peso_por_unidad' => 'required|numeric|min:0',
            'porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'porcentaje_iva' => 'required|numeric|min:0|max:100',
            'categoria' => 'required|string|max:255',
        ]);

        $producto = Producto::findOrFail($id);
        $pesoNeto = $request->peso_por_unidad * $request->stock;
        $monto_descuento = ($request->precio_consumidor * $request->porcentaje_descuento) / 100;
        $monto_iva = (($request->precio_consumidor - $monto_descuento) * ($request->porcentaje_iva / 100));

        $producto->update(array_merge($request->all(), [
            'peso_neto' => $pesoNeto,
            'monto_descuento' => $monto_descuento,
            'monto_iva' => $monto_iva,
            'precio_neto' => $request->precio_consumidor - $monto_descuento + $monto_iva,
        ]));

        return response()->json(['success' => 'Producto actualizado con éxito.', 'producto' => $producto]);
    }

    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json(['message' => 'Producto eliminado con éxito']);
    }

    public function reducirStock(Request $request, $id)
    {
        $producto = Producto::find($id);
    
        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }
    
        // Restar la cantidad vendida al stock actual
        $producto->stock -= $request->input('cantidad');
        
        // Validar que el stock no sea negativo
        if ($producto->stock < 0) {
            return response()->json(['error' => 'Stock insuficiente'], 400);
        }
    
        $producto->save();
    
        return response()->json(['message' => 'Stock actualizado correctamente'], 200);
    }
}
