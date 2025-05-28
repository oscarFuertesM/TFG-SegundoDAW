<?php

namespace App\Http\Controllers;

use App\Models\Poke;
use Illuminate\Http\JsonResponse;

class PokemonController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $pokes = Poke::all(); 
            return response()->json($pokes); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'No se pudieron obtener los Pokémon',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
