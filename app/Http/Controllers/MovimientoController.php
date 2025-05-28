<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\JsonResponse;

class MovimientoController extends Controller
{
    public function index(): JsonResponse
    {
        $movimientos = Movimiento::all();
        return response()->json($movimientos);
    }
}

