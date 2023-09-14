<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('email',$request->email)->first();
        if($user)
        {
            if($user->is_admin)
                return response()->json([
                    'message' => 'Admins cannot login here.'
                ], 401);
        }
        
        return $next($request);
    }
}
