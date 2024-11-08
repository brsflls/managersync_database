<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    // Recuperar todos los clientes.
    public function index(Request $request)
    {
        $clientes = Cliente::all(); // Obtiene todos los clientes
        \Log::info('Clientes: ', $clientes->toArray()); // Registro de los clientes
    
        // Devuelve siempre los clientes en formato JSON
        return response()->json($clientes, 200);
    }

    // Mostrar el formulario para crear un nuevo cliente.
    public function create()
    {
        $clientes = Cliente::all(); // Recuperar todos los clientes
        return view('clientes', compact('clientes')); // Pasar la lista de clientes a la vista
    }

    // Mostrar el formulario para editar un cliente.
    public function edit($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return view('edit_cliente', compact('cliente'))->withErrors(session('errors')); // Pasar los errores
    }

    // Recuperar un cliente específico.
    public function show($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente, 200);
    }

    // Almacenar un nuevo cliente.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'cedula' => 'required|string|max:12|unique:clientes,cedula',
            'empresa_id' => 'required|exists:empresas,id',
        ]);

        $cliente = Cliente::create($validated);

        return response()->json(['success' => 'Cliente creado con éxito.', 'cliente' => $cliente], 201);
    }

    // Actualizar un cliente existente.
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'cedula' => 'required|string|max:12|unique:clientes,cedula,' . $id, // Se excluye el cliente actual
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return response()->json(['success' => 'Cliente actualizado con éxito.', 'cliente' => $cliente]);
    }

    // Eliminar un cliente.
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado con éxito']);
    }
}
