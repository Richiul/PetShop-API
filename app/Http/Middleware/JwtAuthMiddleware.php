<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
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
            $user = $this->authenticateUser();
            if (!$user) {
                return $this->respondNotFound();
            }
        } catch (TokenInvalidException $e) {
            return $this->respondInvalidToken();
        } catch (TokenExpiredException $e) {
            return $this->handleExpiredToken();
        } catch (Exception $e) {
            return $this->respondUnauthorized();
        }

        return $next($request);
    }

    /**
     * Authenticate the user based on the JWT token.
     *
     * @return mixed|null
     */
    private function authenticateUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    /**
     * Respond with a "Not Found" JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondNotFound()
    {
        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Respond with an "Invalid Token" JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondInvalidToken()
    {
        return response()->json(['message' => 'Invalid token'], 401);
    }

    /**
     * Handle an expired token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleExpiredToken()
    {
        $this->invalidateToken();
        return response()->json(['message' => 'Token expired'], 401);
    }

    /**
     * Invalidate the token.
     *
     * @return void
     */
    private function invalidateToken()
    {
        $requestToken = JWTAuth::parseToken();
        $dbToken = JwtToken::where('unique_id', $requestToken)->first();

        if ($dbToken) {
            $dbToken->delete();
        }

        $requestToken->invalidate();
    }

    /**
     * Respond with an "Unauthorized" JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondUnauthorized()
    {
        return response()->json(['message' => 'Authorization token not found'], 401);
    }
}
