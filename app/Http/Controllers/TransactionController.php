<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'transaction' => 'Stake and payout are nullable. If you are charging a fee to use an amusement, use stake. If you are paying out winnings, use payout.',
            'amusement_id' => 'integer',
            'user_id' => 'passed with token',
            'group_id' => 'integer',
            'stake_amount' => 'float',
            'payout_amount' => 'float',
            'stamp_id' => 'integer'

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function all()
    {
        $transactions = Transaction::all();

        return response()->json([
            'transactions' => TransactionResource::collection($transactions),
        ]);
    }

    public function create() {}

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreTransactionRequest $request)
    {
        $validatedData = $request->validated();
        
        $validatedData['user_id'] = $request->user()->id;

        $user = User::findOrFail($validatedData['user_id']);
        $groupId = $validatedData['group_id'];
        $groupUsers = User::where('group_id', $groupId)->get();
        $groupUserCount = $groupUsers->count();

        DB::beginTransaction();
        try {
            // Create the transaction record first
            $transaction = Transaction::create($validatedData);
            
            // Handle stake amount (user pays, group members receive)
            if (isset($validatedData['stake_amount'])) {
                // Deduct from user making the stake
                $user->balance -= $validatedData['stake_amount'];
                $user->save();
                
                // Distribute to group members
                $amountPerUser = $validatedData['stake_amount'] / $groupUserCount;
                foreach ($groupUsers as $groupUser) {
                    $groupUser->balance += $amountPerUser;
                    $groupUser->save();
                }
            }
            
            // Handle payout amount (user receives, group members pay)
            if (isset($validatedData['payout_amount'])) {
                $amountPerUser = $validatedData['payout_amount'] / $groupUserCount;
                
                // Deduct from group members
                foreach ($groupUsers as $groupUser) {
                    $groupUser->balance -= $amountPerUser;
                    $groupUser->save();
                }
                
                // Add to user receiving the payout
                $user->balance += $validatedData['payout_amount'];
                $user->save();
            }
            
            DB::commit();
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
    
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
   

        try {
            $transaction = Transaction::findOrFail($id);



            return new TransactionResource($transaction);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction has been deleted'], 200);
    }
}
