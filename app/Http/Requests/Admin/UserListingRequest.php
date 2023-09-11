<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserListingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer','nullable'],
            'limit' => ['integer','nullable'],
            'desc' => ['in:true,false,0,1','nullable'],
            'sortBy' => ['string','nullable'],
            'first_name' => ['string','nullable'],
            'email' => ['string','nullable'],
            'phone' => ['string','nullable'],
            'address' => ['text','nullable'],
            'created_at' => ['date','nullable'],
            'marketing'=>['in:true,false,0,1','nullable']
        ];
    }
}
