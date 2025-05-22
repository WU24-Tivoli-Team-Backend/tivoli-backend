<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmusementRequest;
use App\Http\Requests\UpdateAmusementRequest;
use App\Models\Amusement;
use App\Http\Resources\AmusementResource;
use App\Models\Group;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Policies\AmusementPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AmusementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $groupId = $request->query('group_id');

        $query = Amusement::query();

        // Only filter by group_id if it's provided
        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $amusements = $query->get();
        return AmusementResource::collection($amusements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmusementRequest $request)
    {
        // Validate the request using the StoreAmusementRequest
        $validatedData = $request->validated();
        $validatedData['group_id'] = $request->user()->group_id;
        $amusement = Amusement::create($validatedData);
        try {
            return response()->json([
                'message' => 'Amusement created successfully.',
                'data'    => new AmusementResource($amusement),
            ], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Amusement not created',
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Amusement $amusement)
    {

        return response()->json(new AmusementResource($amusement));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmusementRequest $request, Amusement $amusement)
    {
        $user = Auth::user();

        // Check if the amusement belongs to the user's group
        if ($amusement->group_id !== $user->group_id) {
            return response()->json(['message' => 'You do not have permission to update this amusement.'], 403);
        }

        $validated = $request->validated();

        // Save old and updated stamp_id
        $oldStampId = $amusement->stamp_id ?? null;
        $newStampId = $validated['stamp_id'] ?? null;

        $amusement->update($validated);

        if ($oldStampId !== $newStampId) {
            $this->handleStampChangeTransaction($amusement, $oldStampId, $newStampId);
        }

        return response()->json([
            'message' => 'Amusement updated successfully.',
            'data' => new AmusementResource($amusement),
        ]);
    }

    /**
     * Create two transactions when stamp_id is changed:
     *  - Payment from the user's group (stake_amount)
     *  - Payout to the recipient group (payout_amount)
     */
    private function handleStampChangeTransaction(Amusement $amusement, ?int $oldStampId, ?int $newStampId)
    {
        $totalAmount = 4.00;

        $senderGroup = $amusement->group;
        $recipientGroupId = 2;  // Our own group
        $recipientGroup = Group::find($recipientGroupId);
        $user = Auth::user();

        $usersInGroup = $senderGroup->users;

        if ($usersInGroup->count() > 0) {
            $amountPerUser = $totalAmount / $usersInGroup->count();

            foreach ($usersInGroup as $groupUser) {
                $groupUser->balance -= $amountPerUser;
                $groupUser->save();

                Transaction::create([
                    'user_id' => $groupUser->id,
                    'group_id' => $senderGroup->id,
                    'stake_amount' => $amountPerUser,
                    'payout_amount' => 0,
                    'amusement_id' => $amusement->id,
                    'stamp_id' => $newStampId,
                ]);
            }
        } else {
            // No users to debit from
        }

        $recipientUsers = $recipientGroup->users;

        if ($recipientUsers->count() > 0) {
            $amountPerRecipientUser = $totalAmount / $recipientUsers->count();

            foreach ($recipientUsers as $recipientUser) {
                $recipientUser->balance += $amountPerRecipientUser;
                $recipientUser->save();

                Transaction::create([
                    'user_id' => $recipientUser->id,
                    'group_id' => $recipientGroup->id,
                    'stake_amount' => 0,
                    'payout_amount' => $amountPerRecipientUser,
                    'amusement_id' => $amusement->id,
                    'stamp_id' => $newStampId,
                ]);
            }
        } else {
            // No users to credit to
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amusement $amusement)
    {
        $user = Auth::user();

        // Check if the amusement belongs to the user's group
        if ($amusement->group_id !== $user->group_id) {
            return response()->json(['message' => 'You do not have permission to update this amusement.'], 403);
        }

        $amusement->delete();

        return response()->json([
            'message' => 'Amusement deleted',
        ], 204);
    }
}
