<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Group;
use App\Models\Amusement;

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
            'stamp_id' => 'nullable|integer|exists:stamps,id|prohibited_if:stake_amount,!null',
        ];
    }

    public function messages()
{
    return [
        'amusement_id.exists' => 'The selected amusement does not exist.',
        'user_id.exists' => 'The selected user does not exist.',
        'group_id.exists' => 'The selected group does not exist.',
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

            if ($validator->errors()->has('user_id') || $validator->errors()->has('group_id') || $validator->errors()->has('amusement_id')) {
                return;
            }

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

            // Check if the amusements stamp is correct
            if (!empty($this->stamp_id)) {
                $amusement = Amusement::find($this->amusement_id);

                if (!$amusement) {
                    $validator->errors()->add('amusement_id', 'Amusement not found.');
                    return;
                }

                if ($amusement->stamp_id !== (int) $this->stamp_id) {
                    $validator->errors()->add('stamp_id', 'The provided stamp does not match the amusement’s stamp.');
                    return;
                }
            }

            $user = User::findOrFail($this->user_id); // Use the actual user_id field

            $groupId = Group::findOrFail($this->group_id);
            $groupUsers = User::where('group_id', $groupId)->get();

            $groupUserCount = count($groupUsers);


        
            //When stake amount is filled out, the following code runs
            $this->whenFilled('stake_amount', function (string $stakeAmount) use ($validator, $user, $groupUserCount, $groupUsers){
                Log::info($stakeAmount);
           

                if ($stakeAmount > $user->balance) {
                    $validator->errors()->add(
                        'stake_amount', // Add error to the stake_amount field
                        'User balance is too low.'
                    );
                    return;
                }


                $amountPerUser = $this->stake_amount / $groupUserCount;
                Log::info("Stake amount per user to receive: $amountPerUser");

                foreach ($groupUsers as $user) {
                    $user->balance += $amountPerUser;
                    $user->save();
                }


            });

            // When payout amount is filled out, the following code runs
            $this->whenFilled('payout_amount', function (string $payoutAmount) use ($validator) {
    
                $groupId = $this->group_id;
                Log::info("Group_id: $groupId");
                Group::findOrFail($groupId);
                $users = User::where('group_id', $groupId)->get();

                $groupBalance = 0;
                foreach ($users as $user) {
                    $groupBalance += $user->balance;
                }
                Log::info("Group balance: $groupBalance");

                if ($payoutAmount > $groupBalance) {
                    $validator->errors()->add(
                        'payout_amount', // Add error to the payout_amount field
                        'Group balance is too low.'
                    );
                    return;
                }

                // Calculate amount per user
                $userCount = count($users);
                $amountPerUser = $payoutAmount / $userCount;
                Log::info("Payout amount to be charged per user: $amountPerUser");

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

                // Pay out the correct stamp
                
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
