<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Amusement;

class StoreVoteRequest extends FormRequest
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
            'amusement_id' => 'required|integer|exists:amusements,id',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'amusement_id.exists' => 'The selected amusement does not exist.',
            'user_id.exists' => 'The selected user does not exist.',
            'user_id.required' => 'The user ID is required.',
            'amusement_id.required' => 'The amusement ID is required.',
            'user_id.integer' => 'The user ID must be an integer.',
            'amusement_id.integer' => 'The amusement ID must be an integer.',
            'amusement_id.prohibited_if' => 'You are not allowed to vote for this amusement.',
            'amusement_id.unique' => 'You have already voted for this amusement.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $userId = $this->input('user_id');
            $amusementId = $this->input('amusement_id');

            $user = User::find($userId);
            $amusement = Amusement::find($amusementId);

            if ($validator->errors()->has('user_id') || $validator->errors()->has('group_id') || $validator->errors()->has('amusement_id')) {
                return;
            }

            // Check if the user belongs to the same group as the amusement
            if ($user && $amusement && $user->group_id === $amusement->group_id) {
                $validator->errors()->add('group', 'You are not allowed to vote for this amusement.');
                return;
            }
        });
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
                    'message' => 'Vote failed',
                    'errors' => $validator->errors()
                ], 500)
            );
        }

        parent::failedValidation($validator);
    }
}
