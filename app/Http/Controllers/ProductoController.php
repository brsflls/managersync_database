<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    // Recuperar todos los productos.
    public function index(Request $request)
    {
        $productos = Producto::all(); // Obtiene todos los productos
        \Log::info('Productos: ', $productos->toArray()); // Registro de los productos
    
        // Devuelve siempre los productos en formato JSON
        return response()->json($productos, 200);
    }

    // Mostrar el formulario para crear un nuevo producto.
    public function create()
    {
        // Este método puede no ser necesario si se hace todo desde una API.
        return view('productoss'); // Puedes modificar esto según tu estructura de vistas
    }

    // Recuperar un producto específico.
    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto, 200);
    }

    // Almacenar un nuevo producto.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo_producto' => 'required|integer',
            'codigo_cabys' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_consumidor' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad_medida' => 'required|string',
            'peso_por_unidad' => 'required|numeric|min:0', // Validación para peso por unidad
            'porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'porcentaje_iva' => 'required|numeric|min:0|max:100', // Porcentaje de IVA
        ]);

        // Cálculo del peso neto
        $pesoNeto = $validated['peso_por_unidad'] * $validated['stock'];

        // Cálculo del monto de descuento
        $monto_descuento = ($validated['precio_consumidor'] * $validated['porcentaje_descuento']) / 100;

        // Cálculo del monto de IVA
        $monto_iva = (($validated['precio_consumidor'] - $monto_descuento) * ($validated['porcentaje_iva'] / 100));

        $producto = Producto::create(array_merge($validated, [
            'peso_neto' => $pesoNeto, // Guardar peso neto calculado
            'monto_descuento' => $monto_descuento,
            'monto_iva' => $monto_iva,
            'precio_neto' => $validated['precio_consumidor'] - $monto_descuento + $monto_iva, // Precio final
        ]));

        return response()->json(['success' => 'Producto creado con éxito.', 'producto' => $producto], 201);
    }

    // Mostrar el formulario para editar un producto.
    public function edit($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto); // Puede devolverlo en JSON para uso en front-end
    }

    // Actualizar un producto existente.
    public function update(Request $request, string $id)
    {
        $request->validate([
            'codigo_producto' => 'required|integer',
            'codigo_cabys' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_consumidor' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unidad_medida' => 'required|string',
            'peso_por_unidad' => 'required|numeric|min:0', // Validación para peso por unidad
            'porcentaje_descuento' => 'nullable|numeric|min:0|max:100',
            'porcentaje_iva' => 'required|numeric|min:0|max:100', // Asegúrate que el IVA sea obligatorio
        ]);

        $producto = Producto::findOrFail($id);

        // Calculo del peso neto
        $pesoNeto = $request->peso_por_unidad * $request->stock;

        // Cálculo del monto de descuento
        $monto_descuento = ($request->precio_consumidor * $request->porcentaje_descuento) / 100;

        // Cálculo del monto de IVA
        $monto_iva = (($request->precio_consumidor - $monto_descuento) * ($request->porcentaje_iva / 100));

        $producto->update(array_merge($request->all(), [
            'peso_neto' => $pesoNeto, // Actualizar peso neto calculado
            'monto_descuento' => $monto_descuento,
            'monto_iva' => $monto_iva,
            'precio_neto' => $request->precio_consumidor - $monto_descuento + $monto_iva, // Precio final
        ]));

        return response()->json(['success' => 'Producto actualizado con éxito.', 'producto' => $producto]);
    }

    // Eliminar un producto.
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
