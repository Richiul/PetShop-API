<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Models\JwtToken;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['login', 'register','forgotPassword','resetPasswordToken']]);
    }

    

    public function register(RegisterRequest $request)
    {

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

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $exists = User::where('email',$request->email)->first();

        if($exists)
            {
                $token = PasswordReset::where('email',$request->email)->first();
                if(!$token)
                {
                    PasswordReset::create(['email'=> $request->email,'token'=>Str::random(64),'created_at'=>now()]);
                    return response()->json([
                        'message' => 'Token sent to your email.',
                    ],200);
                }
                else
                return response()->json([
                    'message' => 'Token already exists',
                ],422);
            }
        else
        return response()->json([
            'message' => 'Email doesn\'t exist',
        ],422);
    }

    public function resetPasswordToken(ResetPasswordRequest $request)
    {
        $passwordReset = PasswordReset::where('email',$request->email)->first();
        
        if($passwordReset)
        {
        $user = $passwordReset->user()->first();

        if($request->password == $request->password_confirmation)
        {
            $user->password = Hash::make($request->password);
            $user->save();

            PasswordReset::where('email',$request->email)->first()->delete();

            return response()->json([
                'message' => 'Password reset successfully',
            ],200);
        }
        else
        return response()->json([
            'message' => 'Password doesn\'t match',
        ],422);
    }else
        return response()->json([
            'message' => 'You don\'t have a valid resetting token.',
        ],401);
    }

    public function index()
    {
        
        return response()->json([
            'user' =>JWTAuth::user(),
            'status' => 'success',
        ],200);
        
    }

    public function delete(DeleteUserRequest $request)
    {
        $user = JWTAuth::user();
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

    public function edit(EditUserRequest $request)
    {
        $user = JWTAuth::user();

        $requestData = $request->all();

        $changedFields = array_diff_assoc($requestData,$user->toArray());

        if(!empty($changedFields))
        {
            if(array_key_exists('password',$changedFields))
            {
                $changedFields['password'] = Hash::make($changedFields['password']);
                $changedFields['password_confirmation'] = Hash::make($changedFields['password_confirmation']);
            }
            $user->update($changedFields);
        }

        return response()->json([
            'message' => 'User '.$user->email.' updated successfully',
            'status' => 'success',
        ],200);
    }

}