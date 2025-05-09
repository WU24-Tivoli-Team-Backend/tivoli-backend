<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAmusementRequest extends FormRequest
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
            'group_id' => 'required|integer|exists:groups,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:attraction,game,activity', // Must be one of the defined enum values
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:255',
            'url' => 'nullable|url|max:255',
        ];
    }
}
