<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'transaction' => 'Stake and payout are nullable. If you are charging a fee to use an amusement, use stake. If you are paying out winnings, use payout.',
            'user_id' => 'passed with token',
            'group_id' => 'string',
            'stake_amount' => 'float',
            'payout_amount' => 'float',
            'stamp' => 'string'

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

    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        $transaction = Transaction::create($validated);

        return response()->json(['ok' => true, 'transaction' => $transaction], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new TransactionResource(Transaction::findOrFail($id));
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
