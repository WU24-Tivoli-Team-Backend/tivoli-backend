<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
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
            'stake_amount' => 'nullable|numeric',
            'payout_amount' => 'nullable|numeric',
            'stamp_id' => 'nullable|integer|exists:stamps,id|prohibited_if:stake_amount,!null',
        ];
    }

    public function messages()
{
    return [
        'amusement_id' => 'required|integer|exists:amusements,id',
        'stake_amount' => 'nullable|numeric',
        'payout_amount' => 'nullable|numeric',
        'stamp_id' => 'nullable|integer|exists:stamps,id|prohibited_if:stake_amount,!null',
    ];
}

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $request = app(Request::class);
        $user = $request->attributes->get('user');
        $group = $request->attributes->get('group');
        if ( $validator->errors()->has('amusement_id')) {
            return;
        }
        // Validation checks only - no database updates
        if ($this->filled('stake_amount') && $this->filled('payout_amount')) {
            $validator->errors()->add('message', 'You cannot provide both stake_amount and payout_amount.');
            return;
        }

        if (!$this->filled('stake_amount') && !$this->filled('payout_amount')) {
            $validator->errors()->add('message', 'You must provide either stake_amount or payout_amount.');
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
     

        // Check balances without modifying them
        $groupUsers = User::where('group_id', $group->id)->get();
        $groupUserCount = count($groupUsers);

        // When stake amount is provided, validate the user has sufficient balance
        if ($this->filled('stake_amount') && $this->stake_amount > $user->balance) {
            $validator->errors()->add('stake_amount', 'User balance is too low.');
            return;
        }

        // When payout amount is provided, validate the group has sufficient balance
        if ($this->filled('payout_amount')) {
            $groupBalance = $groupUsers->sum('balance');
            
            if ($this->payout_amount > $groupBalance) {
                $validator->errors()->add('payout_amount', 'Group balance is too low.');
                return;
            }
            
            // Check individual user balances
            $amountPerUser = $this->payout_amount / $groupUserCount;
            foreach ($groupUsers as $groupUser) {
                if ($groupUser->balance < $amountPerUser) {
                    $validator->errors()->add(
                        'payout_amount', 
                        "User {$groupUser->name} (ID: {$groupUser->id}) has insufficient balance."
                    );
                    return;
                }
            }
        }
    });
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
                    'message' => 'Transaction was not created.',
                    'errors' => $validator->errors()
                ], 500)
            );
        }

        parent::failedValidation($validator);
    }
}
