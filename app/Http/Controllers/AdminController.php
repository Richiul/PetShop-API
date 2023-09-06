<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UserListingRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;


class AdminController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth:api'], ['except' => ['login', 'register']]);
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
            'is_admin' => 1,
            'avatar' => $request->avatar ?? '',
            'is_marketing' => $request->is_marketing ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $token = $this->createJwtTokenDb($user);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Admin created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

        public function index(UserListingRequest $request)
        {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 15;
            $sortBy = $request->sortBy ?? 'id';
            $desc = $request->desc ?? true;
            
            $users = User::paginate($limit)->sortBy($sortBy,($desc) ? 'desc' : 'asc');

            
            return response()->json([
                'status' => 'success',
                'message' => 'Users listed successfully.',
                'users' => $users
            ]);
        }
    
}
