<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Group;

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
            'type' => 'required|string|in:attraction,game', // Must be one of the defined enum values
            'description' => 'nullable|string|max:1000',
            'image_url' => 'nullable|url|max:255',
            'url' => 'nullable|url|max:255',
            'stamp_id' => 'nullable|integer|exists:stamps,id',
        ];
    }

    public function messages() {
        return [
            'group_id.exists' => 'The selected group does not exist.',
            'name.required' => 'You need to provide a name',
            'type.required' => 'You need to enter a type, either attraction or game'
        ];
    }


       /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Creating the amusement failed.',
                    'errors' => $validator->errors()
                ], 500)
            );
        }

        parent::failedValidation($validator);
    }
}
