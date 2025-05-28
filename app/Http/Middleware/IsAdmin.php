<?php

/*namespace App\Http\Middleware;

ESTO DABA FALLO, ES PARA AUTENTICAR CON EL TOKEN DE SANCTUM SI ES ADMIN O NO, SIGO 
CON OTRAS COSAS Y YA VOLVERÉ A ESTO

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) { 
            return $next($request);
        }

        return response()->json(['message' => 'No autorizado'], 403);
    }
}*/

