<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_comercial' => 'required|string|max:255',
            'rut_id_comercial' => 'required|string|max:255|unique:clientes,rut_id_comercial',
            'direccion' => 'required|string|max:255',
            'categoria' => 'required|in:Regular,Preferencial',
            'contacto_nombre' => 'required|string|max:255',
            'contacto_correo' => 'required|email|max:255',
            'porcentaje_oferta' => 'required|numeric|min:0|max:100',
        ]);

        $cliente = Cliente::create($validatedData);

        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $validatedData = $request->validate([
            'nombre_comercial' => 'sometimes|required|string|max:255',
            'rut_id_comercial' => 'sometimes|required|string|max:255|unique:clientes,rut_id_comercial,' . $id,
            'direccion' => 'sometimes|required|string|max:255',
            'categoria' => 'sometimes|required|in:Regular,Preferencial',
            'contacto_nombre' => 'sometimes|required|string|max:255',
            'contacto_correo' => 'sometimes|required|email|max:255',
            'porcentaje_oferta' => 'sometimes|required|numeric|min:0|max:100',
        ]);

        $cliente->update($validatedData);

        return response()->json($cliente);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        if ($cliente->camisetas()->count() > 0) {
            return response()->json(['message' => 'No se puede eliminar el cliente porque tiene camisetas asociadas'], 400);
        }

        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente']);
    }

    public function camisetas($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }
        
        return response()->json($cliente->camisetas);
    }
}
