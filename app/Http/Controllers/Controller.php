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

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function createJwtTokenDb($user,$type='Login')
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
        ]);
    }
    }

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
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to generate token'], 500);
        }

       if($token)
       {
            $token = $this->createJwtTokenDb($user);
       }else
            return response()->json([
                'message' => 'Unauthorized',
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

    public function logout()
    {
        $user = Auth::user();
        
        if($user)
        {
        $dbtToken = JwtToken::where('user_id',$user->id)->first();
        if($dbtToken->token_title == 'Login token')
        {

        $dbtToken->delete();

        $requestToken = JWTAuth::parseToken();
        $requestToken->invalidate();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    
    }
}
else
{
    return response()->json(['error'=>'Unauthenticated.']);
}
}
}