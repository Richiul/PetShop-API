<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class EditUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        try {
            JWTAuth::parseToken()->authenticate();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => ['required','string','min:6'],
            'password_confirmation' => ['required','string','min:6','same:password'],
            'address' => ['required','string','max:255'],
            'phone_number' => ['required','string','max:255']
        ];
    }
}
