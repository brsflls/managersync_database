<?php

namespace App\Http\Controllers;

use App\Models\FacturaProductoServicio;
use Illuminate\Http\Request;

class FacturaProductoController extends Controller
{
    public function index()
    {
        $productos = FacturaProductoServicio::all();
        return response()->json($productos, 200);
    }

    public function store(Request $request)
    {
        $producto = FacturaProductoServicio::create($request->all());
        return response()->json($producto, 201);
    }

    public function show($id)
    {
        $producto = FacturaProductoServicio::find($id);
        return response()->json($producto, 200);
    }

    public function update(Request $request, $id)
    {
        $producto = FacturaProductoServicio::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto, 200);
    }

    public function destroy($id)
    {
        FacturaProductoServicio::destroy($id);
        return response()->json(['message' => 'Producto eliminado'], 200);
    }
}
