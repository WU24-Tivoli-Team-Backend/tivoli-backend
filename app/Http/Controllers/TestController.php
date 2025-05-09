<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function ping(Request $request)
    {
        return response([
            'message' => 'Thanks for checking out our amazing API, see you soon!',
            'timestamp' => now(),
            'headers' => 'application/json'
        ], 200);
    }

}
