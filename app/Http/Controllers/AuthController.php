<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JwtToken;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','forgotPassword','resetPasswordToken']]);
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

    public function logout(Request $request)
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

    public function forgotPassword(Request $request)
    {
        $exists = User::where('email',$request->email)->first();

        if($exists)
            {
                $this->createJwtTokenDb($exists,'Forgot Password');
            }
        else
        return response()->json([
            'message' => 'Email doesn\'t exist',
        ],422);
    }

    public function resetPasswordToken(Request $request)
    {
        $token = JwtToken::where('unique_id',$request->token)->first();
        $user = $token->user()->first();

        if($request->password == $request->password_confirmation)
        {
            $user->password = Hash::make($request->password);
            $user->save();

            PasswordReset::create(['email' => $request->email,'token' => $token->unique_id,'created_at'=>now()]);
            $token->delete();
            return response()->json([
                'message' => 'Password reset successfully',
            ],200);
        }
        else
        return response()->json([
            'message' => 'Password doesn\'t match',
        ],422);
    }

    public function index()
    {
        
        return response()->json([
            'user' =>Auth::user(),
            'status' => 'success',
        ],200);
        
    }

    public function delete()
    {
        $user = Auth::user();
        $userEmail = $user->email;
        JwtToken::where('user_id',$user->id)->where('token_title','Login token')->first()->delete();
        $requestToken = JWTAuth::parseToken();
        $requestToken->invalidate();
        User::where('id',$user->id)->first()->delete();

        

        return response()->json([
            'message' => 'User '.$userEmail.' deleted successfully',
            'status' => 'success',
        ],200);
    }

    public function edit(Request $request)
    {
        $user = Auth::user();

        $requestData = $request->all();

        $changedFields = array_diff_assoc($requestData,$user->toArray());

        if(!empty($changedFields))
        {
            $user->update($changedFields);
        }
        
        return response()->json([
            'message' => 'User '.$user->email.' updated successfully',
            'status' => 'success',
        ],200);
    }
}