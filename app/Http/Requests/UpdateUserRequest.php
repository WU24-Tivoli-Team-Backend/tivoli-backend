<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust later to true for authorization
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'image_url' => 'nullable|url',
            'github'    => 'nullable|string|max:255',
            'url'       => 'nullable|url',
        ];
    }
}
