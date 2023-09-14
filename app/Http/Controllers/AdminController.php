<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\DeleteUserFromAdminRequest;
use App\Http\Requests\Admin\EditUserFromAdminRequest;
use App\Http\Requests\Admin\UserListingRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt.auth', 'authorized.admin'], ['except' => ['login', 'register']]);
    }
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'is_admin' => 1,
            'avatar' => $request->avatar,
            'is_marketing' => $request->is_marketing ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $token = $this->createJwtTokenDb($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin created successfully',
            'data' => $user,
            'authorisation' => [
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
    public function index(UserListingRequest $request)
    {

        $page = $request->page ?? 1;

        $limit = $request->limit ?? 15;
        $nonAdmins = User::where('is_admin', false);

        $maxPages = round($nonAdmins->count() / $limit) < 1 ? 1 : round($nonAdmins->count() / $limit);

        if ($page > $maxPages)
            return response()->json([
                'status' => 'error',
                'message' => 'The database doesn\'t have that many users.'
            ], 422);

        $sortBy = $request->sortBy ?? 'id';
        $desc = (!$request->desc || $request->desc === 'true' || $request->desc === 1) ? true : false;

        if ($request->first_name)
            $nonAdmins = $nonAdmins->where('first_name', 'like', '%' . $request->first_name . '%');

        if ($request->email)
            $nonAdmins = $nonAdmins->where('email', 'like', '%' . $request->email . '%');

        if ($request->phone)
            $nonAdmins = $nonAdmins->where('phone', 'like', '%' . $request->phone . '%');

        if ($request->address)
            $nonAdmins = $nonAdmins->where('address', 'like', '%' . $request->address . '%');

        if ($request->created_at)
            $nonAdmins = $nonAdmins->where('created_at', $request->created_at);

        if ($request->is_marketing)
            $nonAdmins = $nonAdmins->where('is_marketing', $request->is_marketing);

        $users = $nonAdmins->orderBy($sortBy, ($desc) ? 'desc' : 'asc')->paginate($limit);
        return response()->json([
            'status' => 'success',
            'message' => 'Users listed successfully.',
            'data' => $users
        ], 200);
    }
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function edit(EditUserFromAdminRequest $request, string $uuid)
    {

        $user = User::where('uuid', $uuid)->first();

        if (!$user)
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);

        $requestData = $request->all();

        $changedFields = array_diff_assoc($requestData, $user->toArray());

        if (is_array($changedFields) && !empty($changedFields)) {

            if (array_key_exists('password', $changedFields)) {
                $changedFields['password'] = Hash::make($changedFields['password']);
                $changedFields['password_confirmation'] = Hash::make($changedFields['password_confirmation']);
            }

            $user->update($changedFields);
        }

        return response()->json([
            'message' => 'User ' . $user->email . ' updated successfully',
            'data' => $user,
            'status' => 'success'
        ], 200);
    }
    /**
     * JSON response.
     *
     * @return JsonResponse|array<mixed>
     */
    public function delete(string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if (!$user)
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);

        $userEmail = $user->email;
        if (JwtToken::where('user_id', $user->id)->where('token_title', 'Login token')->first()) {
            $token = JwtToken::where('user_id', $user->id)->where('token_title', 'Login token')->first();
            $token->delete();
        }

        $user->delete();

        return response()->json([
            'message' => 'User ' . $userEmail . ' deleted successfully',
            'status' => 'success',
        ], 200);
    }
}
