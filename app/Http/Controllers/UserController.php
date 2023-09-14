<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt.auth'], ['except' => ['login', 'register', 'forgotPassword', 'resetPasswordToken']]);
    }
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->createUser($request);

        if (!$user) {
            return $this->errorResponse('User creation failed', 500);
        }

        $token = $this->createJwtTokenDb($user);

        return $this->successResponse([
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    /**
     * Forgot password request.
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $exists = User::where('email', $request->email)->first();

        if ($exists) {
            return $this->sendPasswordResetToken($request->email);
        }

        return $this->errorResponse('Email doesn\'t exist', 422);
    }

    /**
     * Reset password with token.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPasswordToken(ResetPasswordRequest $request): JsonResponse
    {
        $passwordReset = PasswordReset::where('email', $request->email)->first();

        if ($passwordReset) {
            $user = $passwordReset->user()->first();

            if ($this->resetUserPassword($request, $user)) {
                $passwordReset->delete();
                return $this->successResponse('Password reset successfully');
            }

            return $this->errorResponse('Password doesn\'t match', 422);
        }

        return $this->errorResponse('You don\'t have a valid resetting token.', 401);
    }

    /**
     * Delete the authenticated user.
     *
     * @return JsonResponse
     */
    public function delete(): JsonResponse
    {
        $user = JWTAuth::user();

        if (!$user instanceof User) {
            return $this->errorResponse('User not found', 422);
        }

        $userEmail = $user->email;
        $this->deleteJwtToken($user->id);
        $this->invalidateToken();

        if ($this->deleteUser($user)) {
            return $this->successResponse('User ' . $userEmail . ' deleted successfully');
        }

        return $this->errorResponse('User deletion failed', 500);
    }

    /**
     * Edit the authenticated user's profile.
     *
     * @param EditUserRequest $request
     * @return JsonResponse
     */
    public function edit(EditUserRequest $request): JsonResponse
    {
        $user = JWTAuth::user();

        if (!$user instanceof User) {
            return $this->errorResponse('User not found', 422);
        }

        $requestData = $request->all();
        $changedFields = array_diff_assoc($requestData, $user->toArray());

        if (is_array($changedFields) && !empty($changedFields)) {
            $this->updateUser($user, $changedFields);
        }

        return $this->successResponse('User ' . $user->email . ' updated successfully');
    }

    /**
     * Create a new user.
     *
     * @param RegisterRequest $request
     * @return User|null
     */
    private function createUser(): ?User
    {
        // ... Create and return the user ...

        return $user;
    }

    /**
     * Send a password reset token.
     *
     * @param string $email
     * 
     * @return JsonResponse
     */
    private function sendPasswordResetToken(): JsonResponse
    {
        // ... Send the password reset token and return a response ...

        return $this->successResponse('Token sent to your email.');
    }

    /**
     * Reset the user's password.
     *
     * @param ResetPasswordRequest $request
     * 
     * @param User $user
     * 
     * @return bool
     */
    private function resetUserPassword(): bool
    {
        // ... Reset the user's password and return success or failure ...

        return true; // Replace with your logic
    }

    /**
     * Delete the user.
     *
     * @param User $user
     * 
     * @return bool
     */
    private function deleteUser(): bool
    {
        // ... Delete the user and return success or failure ...

        return true; // Replace with your logic
    }

    /**
     * Update the user's profile.
     *
     * @param User $user
     * 
     * @param array $fields
     */
    private function updateUser(): void
    {
        // ... Update the user's profile ...

        // Example:
        // $user->update($fields);
    }

    // Other helper functions for JWT token management

    // ...

    /**
     * JSON response for success.
     *
     * @return JsonResponse
     */
    private function successResponse(mixed $data): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    /**
     * JSON response for error.
     *
     * @return JsonResponse
     */
    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $statusCode);
    }
}
