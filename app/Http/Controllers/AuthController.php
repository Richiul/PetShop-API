<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JwtToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email','password');
       if( Auth::attempt($credentials))
       {
        $user = Auth::user();
        $token = $this->createJwtTokenDb($user);

       }else
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'avatar' => 'nullable|string',
            'is_marketing' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $user = User::create([
            'uuid' => Uuid::uuid4()->toString() ?? '',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'avatar' => $request->avatar ?? '',
            'is_marketing' => $request->is_marketing ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $token = $this->createJwtTokenDb($user);
        
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function createJwtTokenDb($user)
    {
        if(!JwtToken::where('user_id',$user->id)->first())
        {
        $issuer = ['iss' => env('JWT_ISSUER')];
        $tokenFromUser = JWTAuth::claims($issuer)->fromUser($user);
        JwtToken::create([
            'user_id'=> $user->id,
            'unique_id' => $tokenFromUser,
            'token_title' => 'Login token'
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

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        $dbtToken = JwtToken::where('user_id',$user->id)->first();

        if($dbtToken)
        {
            $dbtToken->delete();
        }

        $requestToken = JWTAuth::parseToken();
        $requestToken->invalidate();
        
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}