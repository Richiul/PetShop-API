<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class EditUserFromAdminRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422)
            );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int,string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required','string','max:255'],
            'last_name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255'],
            'password' => ['required','string','min:6'],
            'password_confirmation' => ['required','string','min:6','same:password'],
            'address' => ['required','string','max:255'],
            'phone_number' => ['required','string','max:255'],
            'avatar' => ['nullable','string'],
            'is_marketing' => ['nullable','boolean']
        ];
    }
}
