<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Amusement;
use Illuminate\Http\Request;

class StoreTransactionRequest extends FormRequest
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
            'stake_amount' => 'nullable|numeric',
            'payout_amount' => 'nullable|numeric',
            'stamp_id' => 'nullable|integer|exists:stamps,id|prohibited_if:stake_amount,present',
        ];
    }

    public function messages()
    {
        return [
            'amusement_id.exists' => 'The selected amusement does not exist.',
            'stamp_id.exists' => 'The selected stamp does not exist.',
            'stamp_id.prohibited_if' => 'A stamp cannot be used when stake amount is provided.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $request = app(Request::class);
            $user = $request->attributes->get('user');
            $group = $request->attributes->get('group');

            if ($validator->errors()->has('amusement_id')) {
                return;
            }

            // Validation checks only - no database updates
            if ($this->filled('stake_amount') && $this->filled('payout_amount')) {
                $validator->errors()->add('message', 'You cannot provide both stake_amount and payout_amount.');
                return;
            }

            // Get the amusement first - we'll need it for multiple checks
            $amusement = Amusement::find($this->amusement_id);

            if (!$amusement) {
                $validator->errors()->add('amusement_id', 'Amusement not found.');
                return;
            }

            // Check if the amusements stamp is correct

            if (!empty($this->stamp_id)) {
                if ($amusement->stamp_id !== (int) $this->stamp_id) {
                    $validator->errors()->add('stamp_id', 'The provided stamp does not match the amusement\'s stamp.');
                    return;
                }
            }

            // Check balances without modifying them
            $groupUsers = User::where('group_id', $group->id)->get();

            // When stake amount is provided, validate the user has sufficient balance
            if ($this->filled('stake_amount') && $this->stake_amount > $user->balance) {
                $validator->errors()->add('stake_amount', 'User balance is too low.');
                return;
            }

            // When stake amount is provided, do not allow a stamp
            if ($this->filled('stake_amount') && $this->filled('stamp_id')) {
                $validator->errors()->add('stamp_id', 'A stamp cannot be used when stake amount is provided.');
                return;
            }

            // An attraction can only provide a stamp
            if ($amusement->type === 'attraction' && $this->filled('payout_amount')) {
                $validator->errors()->add('payout_amount', 'An attraction can only provide a stamp.');
                return;
            }

            // Always pay out a stamp
            if (!$this->filled('stake_amount') && !$this->filled('stamp_id')) {
                $validator->errors()->add('stamp_id', 'A stamp must be provided.');
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
        Log::error('Validation errors', $validator->errors()->toArray());

        if ($this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 500)
            );
        }
        parent::failedValidation($validator);
    }
}
