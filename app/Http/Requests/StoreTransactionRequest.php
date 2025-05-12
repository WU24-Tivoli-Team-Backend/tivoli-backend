<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Models\User;
use App\Models\Group;

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
            'user_id' => 'required|integer|exists:users,id',
            'group_id' => 'required|integer|exists:groups,id',
            'stake_amount' => 'nullable|numeric',
            'payout_amount' => 'nullable|numeric',
            'stamp_id' => 'nullable|string|exists:stamps,id|prohibited_if:stake_amount,!null',
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
            // Check if both stake_amount and payout_amount are provided
            if ($this->filled('stake_amount') && $this->filled('payout_amount')) {
                $validator->errors()->add(
                    'message',
                    'You cannot provide both stake_amount and payout_amount.'
                );
                return;
            }

            // Check if neither stake_amount nor payout_amount is provided
            if (!$this->filled('stake_amount') && !$this->filled('payout_amount')) {
                $validator->errors()->add(
                    'message',
                    'You must provide either stake_amount or payout_amount.'
                );
                return;
            }


            $this->whenFilled('stake_amount', function (string $stakeAmount) use ($validator) {
                $user = User::findOrFail($this->user_id); // Use the actual user_id field

                if ($stakeAmount > $user->balance) {
                    $validator->errors()->add(
                        'stake_amount', // Add error to the stake_amount field
                        'User balance is too low.'
                    );
                    return;
                }

                $groupId = $this->group_id;
                Group::findOrFail($groupId);
                $users = User::where('group_id', $groupId)->get();

                $userCount = count($users);
                $amountPerUser = $this->stake_amount / $userCount;

                foreach ($users as $user) {
                    $user->balance += $amountPerUser;
                    $user->save();
                }


            });

            $this->whenFilled('group_id', function (string $group) use ($validator) {
                $groupId = $this->group_id;
                Group::findOrFail($groupId);
                $users = User::where('group_id', $groupId)->get();

                $groupBalance = 0;
                foreach ($users as $user) {
                    $groupBalance += $user->balance;
                }

                if ($this->payout_amount > $groupBalance) {
                    $validator->errors()->add(
                        'payout_amount', // Add error to the payout_amount field
                        'Group balance is too low.'
                    );
                    return;
                }

                // Calculate amount per user
                $userCount = count($users);
                $amountPerUser = $this->payout_amount / $userCount;

                // Charge each user
                foreach ($users as $user) {
                    // Make sure user has enough balance
                    if ($user->balance >= $amountPerUser) {
                        $user->balance -= $amountPerUser;
                        $user->save();
                    } else {
                        // Handle case where an individual user doesn't have enough
                        $validator->errors()->add(
                            'payout_amount',
                            "User {$user->name} (ID: {$user->id}) has insufficient balance."
                        );
                        return; // Stop execution
                    }
                }
            });
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 500)
            );
        }

        parent::failedValidation($validator);
    }
}
