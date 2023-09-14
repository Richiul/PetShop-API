<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {

                return response()->json(['message' => 'Invalid token'], 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $requestToken = JWTAuth::parseToken();
                $dbtToken = JwtToken::where('unique_id',$requestToken)->first();

                if ($dbtToken) {
                    $dbtToken->delete();
                }

                $requestToken->invalidate();

                return response()->json(['message' => 'Token expired'], 401);
            } else {
                return response()->json(['message' => 'Authorization token not found'], 401);
            }
        }

        return $next($request);
    }
}
