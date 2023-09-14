<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Models\JwtToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
/**
 * JSON response.
 *
 * @return JsonResponse|array<mixed>|string
 */
    public function createJwtTokenDb(User $user,string $type='Login')
    {
        
        if(!JwtToken::where('user_id',$user->id)->first())
        {
        $issuer = ['iss' => env('JWT_ISSUER')];
        $tokenFromUser = JWTAuth::claims($issuer)->fromUser($user);
        $expiresAt = Carbon::now()->addHours(2);
            JwtToken::create([
                'user_id'=> $user->id,
                'unique_id' => $tokenFromUser,
                'token_title' => $type.' token',
                'expires_at' => $expiresAt,
                'last_used_at' => Carbon::now(),
                'refresheed_at' => Carbon::now()
            ]);
        return $tokenFromUser;
    }
        else
        {
        return response()->json([
            'message' => 'You are already logged in on another window.',
        ],401);
    }
    }
/**
 * JSON response.
 *
 * @return JsonResponse|array<mixed>
 */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        if (!password_verify($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        try {
            // Generate a JWT token for the user
            $token = JWTAuth::fromUser($user);
            $token = $this->createJwtTokenDb($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to generate token'], 500);
        }

        if(!is_string($token))
            return response()->json([
                'message' => 'Unauthorized, already logged in',
            ], 401);

        $user->last_login_at = now();
        $user->save();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
/**
 * JSON response.
 *
 * @return JsonResponse|array<mixed>
 */
    public function logout()
    {
        $user = JWTAuth::user();
        
        if($user instanceof User)
        {
        $dbtToken = JwtToken::where('user_id',$user->id)->first();
        if($dbtToken)
        {


        $dbtToken->delete();

        $requestToken = JWTAuth::parseToken();
        $requestToken->invalidate();

        return response()->json([
            'message' => 'Successfully logged out',
        ],200);
    }
    else
    return response()->json([
        'message' => 'Token not found logged out',
    ],422);
    
    
    }
    else
        return response()->json(['message'=>'Unauthenticated.'],401);
}
}