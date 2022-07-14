<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Recoger token de la cabecera
        $token = $request->header('Authorization');
        $jwt_auth = new \JwtAuth();
        $check_token = $jwt_auth->check_token($token);

        if($check_token) {
            return $next($request);

        } else {
            // Devolver mensaje de error de no identificado
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'ERROR: El usuario no esta identificado.'
            );
            return response()->json($data, $data['code']);

        }

    }
}
