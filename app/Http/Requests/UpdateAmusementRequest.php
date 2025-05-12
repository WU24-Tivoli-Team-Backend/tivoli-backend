<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAmusementRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'group_id' => 'sometimes|integer|exists:groups,id',
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:attraction,game,activity',
            'description' => 'sometimes|string|max:1000',
            'image_url' => 'sometimes|url|max:255',
            'url' => 'sometimes|url|max:255',
        ];
    }
}
