<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'datosEquipo' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $equipo = Equipo::create([
                'datosEquipo' => $request->datosEquipo,
            ]);

            $user = Auth::user();
            $equipo->usuarios()->attach($user->idUsuario);

            DB::commit();

            return response()->json(['message' => 'Equipo creado correctamente', 'equipo' => $equipo], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear el equipo', 'error' => $e->getMessage()], 500);
        }
    }


public function misEquipos()
{
    $user = Auth::user();
    Log::info('Usuario autenticado:', ['user' => $user]);
    Log::info('Tipo de usuario:', ['type' => get_class($user)]);
    if (!$user) {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }
    $equipos = $user->equiposRelacion()->get();
    return response()->json($equipos);
}

}

