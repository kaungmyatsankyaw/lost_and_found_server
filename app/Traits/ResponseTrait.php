<?php

namespace App\Traits;

trait ResponseTrait
{

    public function successResponse($_data)
    {
        return response()->json($_data)->setStatusCode(200);
    }

    public function failResponse($_data)
    {
        return response()->json($_data)->setStatusCode(422);
    }

    public function unAuthResponse($_data)
    {
        return response()->json($_data)->setStatusCode(401);
    }

    public function badRequestResponse($_data){
        return response()->json($_data)->setStatusCode(400);
    }

}
