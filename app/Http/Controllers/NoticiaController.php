<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NoticiaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Noticia::all());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'texto' => 'required|string',
        ]);

        $noticia = Noticia::create($validated);

        return response()->json($noticia, 201);
    }

    public function show($id): JsonResponse
    {
        $noticia = Noticia::findOrFail($id);
        return response()->json($noticia);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $noticia = Noticia::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string',
            'texto' => 'required|string',
        ]);

        $noticia->update($validated);

        return response()->json($noticia);
    }

    public function destroy($id): JsonResponse
    {
        $noticia = Noticia::findOrFail($id);
        $noticia->delete();

        return response()->json(['message' => 'Noticia eliminada']);
    }
}

