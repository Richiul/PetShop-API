<?php

namespace App\Http\Requests\Main;

use Illuminate\Foundation\Http\FormRequest;

class PromotionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int,string>>
     */
    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer'],
            'limit' => ['nullable', 'integer'],
            'sortBy' => ['nullable', 'string'],
            'desc' => ['nullable', 'in:0,1,true,false'],
            'valid' => ['nullable', 'in:0,1,true,false'],
        ];
    }
}
