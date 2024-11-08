<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    // Método para obtener todas las empresas
    public function index()
    {
        return Empresa::all();
    }

    // Método para almacenar una nueva empresa
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:empresas',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'nullable|email',
            'cedula_empresa' => 'required|string|max:12|unique:empresas',
            'provincia' => 'nullable|string|max:255',
            'canton' => 'nullable|string|max:255',
            'distrito' => 'nullable|string|max:255',
            'otras_senas' => 'nullable|string',
            'codigo_actividad'=> 'required|string|max:12|unique:empresas',
            'descripcion' => 'required|string',
            'empresa' =>'required|string', 
            
        ]);

        $empresa = Empresa::create($request->all());
        return response()->json($empresa, 201);
    }

    // Método para mostrar una empresa específica
    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        return response()->json($empresa);
    }

    // Método para actualizar una empresa
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:empresas',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'nullable|email',
            'cedula_empresa' => 'required|string|max:12|unique:empresas',
            'provincia' => 'nullable|string|max:255',
            'canton' => 'nullable|string|max:255',
            'distrito' => 'nullable|string|max:255',
            'otras_senas' => 'nullable|string',
            'codigo_actividad'=> 'required|string|max:12|unique:empresas',
            'descripcion' => 'required|string',
            'empresa' =>'required|string', 
        ]);


        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return response()->json($empresa);
    }

    // Método para eliminar una empresa
    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();
        return response()->json(null, 204);
    }
}
