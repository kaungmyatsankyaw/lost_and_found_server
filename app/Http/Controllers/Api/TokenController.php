<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotiToken;

class TokenController extends Controller
{
    public function storeToken(Request $request)
    {
        NotiToken::updateOrCreate(
            [
                'udid' => $request->get('udid'),
            ],
            [
                'token' => $request->get('token')
            ]
        );
    }

    public function updateToken(Request $request)
    {
        NotiToken::updateOrCreate(
            [
                'udid' => $request->get('udid'),
            ],
            [
                'token' => $request->get('token'),
                'status' => $request->get('status')
            ]
        );
    }
}
