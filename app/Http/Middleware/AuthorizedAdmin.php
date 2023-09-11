<?php

namespace App\Http\Middleware;

use App\Models\JwtToken;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizedAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->header('authorization') ?? null;
        
        if($bearerToken != null)
        {
            $bearerToken = explode(' ',$bearerToken);
            $token = implode(' ', array_slice($bearerToken, 1));
        
        $isValid = JwtToken::where('unique_id',$token)->first();
        }
        
        $user = User::where('id',$isValid->user_id)->first();
        
        if(!$user->is_admin)
            return response()->json([
                'message' => 'Users cannot login here.'
            ], 401);
        
        return $next($request);
    }
}
