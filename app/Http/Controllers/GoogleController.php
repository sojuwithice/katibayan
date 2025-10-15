<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function getEmailFromToken(Request $request)
    {
        $client = new Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($request->token);

        if ($payload) {
            return response()->json(['email' => $payload['email']]);
        }

        return response()->json(['error' => 'Invalid token'], 400);
    }
}
