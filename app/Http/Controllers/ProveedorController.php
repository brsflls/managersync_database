<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    // Recuperar todos los proveedores.
    public function index()
    {
        $proveedores = Proveedor::all();
        return response()->json($proveedores, 200);
    }

    // Almacenar un nuevo proveedor.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'email' => 'required|email',
            'cedula_juridica' => 'required|string|size:12|unique:proveedors',
        ]);

        try {
            $proveedor = Proveedor::create($validated);
            return response()->json($proveedor, 201); // Respuesta JSON para la creación
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el proveedor: ' . $e->getMessage()], 500);
        }
    }

    // Actualizar un proveedor existente.
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'email' => 'required|email',
            'cedula_juridica' => 'required|string|size:12|unique:proveedors,cedula_juridica,' . $id,
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($validated);

        return response()->json($proveedor, 200);
    }

    // Eliminar un proveedor.
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return response()->json(['message' => 'Proveedor eliminado con éxito.'], 200);
    }

    // Mostrar los detalles de un proveedor específico.
    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return response()->json($proveedor, 200);
    }
}
