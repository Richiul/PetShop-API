<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer','required'],
            'limit' => ['integer','required'],
            'desc' => ['boolean','required'],
            'sortBy' => ['string','required'],
            'first_name' => ['string','nullable'],
            'email' => ['string','nullable'],
            'phone' => ['string','nullable'],
            'address' => ['text','nullable'],
            'created_at' => ['date','nullable'],
            'marketing'=>['boolean','nullable']
        ];
    }
}
