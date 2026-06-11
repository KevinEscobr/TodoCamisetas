<?php

namespace App\Http\Controllers;

use App\Models\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    public function index()
    {
        return response()->json(Talla::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tallas,nombre|max:255'
        ]);

        $talla = Talla::create($validated);
        return response()->json($talla, 201);
    }

    public function show($id)
    {
        $talla = Talla::find($id);
        if (!$talla) return response()->json(['message' => 'Talla no encontrada'], 404);
        return response()->json($talla);
    }

    public function update(Request $request, $id)
    {
        $talla = Talla::find($id);
        if (!$talla) return response()->json(['message' => 'Talla no encontrada'], 404);

        $validated = $request->validate([
            'nombre' => 'required|string|unique:tallas,nombre,' . $id . '|max:255'
        ]);

        $talla->update($validated);
        return response()->json($talla);
    }

    public function destroy($id)
    {
        $talla = Talla::find($id);
        if (!$talla) return response()->json(['message' => 'Talla no encontrada'], 404);

        $talla->delete();
        return response()->json(['message' => 'Talla eliminada correctamente']);
    }
}
