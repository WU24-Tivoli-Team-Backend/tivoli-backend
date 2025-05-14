<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiKeyController extends Controller
{
    /**
     * Validate an API key and return the associated group ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $apiKey = $request->input('api_key');
        $groupId = $this->findGroupIdByApiKey($apiKey);

        if ($groupId === null) {
            return response()->json(['error' => 'Invalid API key.'], 422);
        }

        return response()->json([
            'message' => 'API key validated successfully',
            'group_id' => $groupId
        ]);
    }

    /**
     * Find the group ID associated with an API key.
     *
     * @param  string  $apiKey
     * @return int|null
     */
    private function findGroupIdByApiKey(string $apiKey): ?int
    {
        $apiKeys = config('api-keys.groups');
        
        foreach ($apiKeys as $groupId => $configApiKey) {
            if ($configApiKey === $apiKey) {
                return $groupId;
            }
        }
        
        return null;
    }
}