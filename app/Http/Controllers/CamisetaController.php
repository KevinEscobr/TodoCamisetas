<?php

namespace App\Http\Controllers;

use App\Models\Camiseta;
use Illuminate\Http\Request;

class CamisetaController extends Controller
{
    public function index(Request $request)
    {
        $clienteId = $request->query('cliente_id');
        $cliente = $clienteId ? \App\Models\Cliente::find($clienteId) : null;

        $camisetas = Camiseta::all()->map(function ($camiseta) use ($cliente) {
            $precioFinal = $camiseta->precio;
            
            if ($cliente && $cliente->categoria === 'Preferencial') {
                if (!is_null($camiseta->precio_oferta)) {
                    $precioFinal = $camiseta->precio_oferta;
                } elseif ($cliente->porcentaje_oferta > 0) {
                    $precioFinal = $camiseta->precio - ($camiseta->precio * ($cliente->porcentaje_oferta / 100));
                }
            }
            
            $camiseta->precio_final = $precioFinal;
            return $camiseta;
        });

        return response()->json($camisetas);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'club' => 'required|string|max:255',
            'pais' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'precio' => 'required|integer',
            'precio_oferta' => 'nullable|integer',
            'cantidad' => 'required|integer|min:0',
            'tallas' => 'required|array',
            'tallas.*' => 'exists:tallas,id',
            'detalles' => 'nullable|string',
            'sku' => 'required|string|unique:camisetas,sku|max:255',
        ]);

        $tallas = $validatedData['tallas'] ?? [];
        unset($validatedData['tallas']);

        $camiseta = Camiseta::create($validatedData);
        $camiseta->tallas()->sync($tallas);

        return response()->json($camiseta, 201);
    }

    public function show(Request $request, $id)
    {
        $camiseta = Camiseta::find($id);
        
        if (!$camiseta) {
            return response()->json(['message' => 'Camiseta no encontrada'], 404);
        }

        $clienteId = $request->query('cliente_id');
        $cliente = $clienteId ? \App\Models\Cliente::find($clienteId) : null;

        $precioFinal = $camiseta->precio;
        if ($cliente && $cliente->categoria === 'Preferencial') {
            if (!is_null($camiseta->precio_oferta)) {
                $precioFinal = $camiseta->precio_oferta;
            } elseif ($cliente->porcentaje_oferta > 0) {
                $precioFinal = $camiseta->precio - ($camiseta->precio * ($cliente->porcentaje_oferta / 100));
            }
        }
        $camiseta->precio_final = $precioFinal;

        return response()->json($camiseta);
    }

    public function update(Request $request, $id)
    {
        $camiseta = Camiseta::find($id);

        if (!$camiseta) {
            return response()->json(['message' => 'Camiseta no encontrada'], 404);
        }

        $validatedData = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'club' => 'sometimes|required|string|max:255',
            'pais' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|required|string|max:255',
            'precio' => 'sometimes|required|integer',
            'precio_oferta' => 'nullable|integer',
            'cantidad' => 'sometimes|required|integer|min:0',
            'tallas' => 'sometimes|required|array',
            'tallas.*' => 'exists:tallas,id',
            'detalles' => 'nullable|string',
            'sku' => 'sometimes|required|string|unique:camisetas,sku,' . $id . '|max:255',
        ]);

        if (isset($validatedData['tallas'])) {
            $tallas = $validatedData['tallas'];
            unset($validatedData['tallas']);
            $camiseta->tallas()->sync($tallas);
        }

        $camiseta->update($validatedData);

        return response()->json($camiseta);
    }

    public function destroy($id)
    {
        $camiseta = Camiseta::find($id);

        if (!$camiseta) {
            return response()->json(['message' => 'Camiseta no encontrada'], 404);
        }

        $camiseta->delete();

        return response()->json(['message' => 'Camiseta eliminada correctamente']);
    }
}
