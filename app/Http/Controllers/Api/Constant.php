<?php


namespace App\Http\Controllers\Api;


class Constant
{
    public static $_createdStatus = 201;
    public static $_unauthorizedStatus = 401;
    public static $_badRequestStatus = 400;
    public static $_internalServerStatus = 500;
    public static $_successStatus=200;

    public static function successResponse($_data, $_message, $_status)
    {
        return response()->json([
            'status' => 1,
            'message' => $_message,
            'data' => $_data
        ], $_status);
    }

    public static function failResponse($_data, $_message, $_status)
    {
        return response()->json([
            'status' => 0,
            'message' => $_message,
            'data' => $_data
        ], $_status);
    }


}
