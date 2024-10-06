<?php

namespace App\Http\Controllers;

use App\Models\FacturaReferencia;
use Illuminate\Http\Request;

class FacturaReferenciaController extends Controller
{
    public function index()
    {
        $referencias = FacturaReferencia::all();
        return response()->json($referencias, 200);
    }

    public function store(Request $request)
    {
        $referencia = FacturaReferencia::create($request->all());
        return response()->json($referencia, 201);
    }

    public function show($id)
    {
        $referencia = FacturaReferencia::find($id);
        return response()->json($referencia, 200);
    }

    public function update(Request $request, $id)
    {
        $referencia = FacturaReferencia::findOrFail($id);
        $referencia->update($request->all());
        return response()->json($referencia, 200);
    }

    public function destroy($id)
    {
        FacturaReferencia::destroy($id);
        return response()->json(['message' => 'Referencia eliminada'], 200);
    }
}
