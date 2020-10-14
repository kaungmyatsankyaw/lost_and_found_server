<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    use ResponseTrait;
    public function login(LoginRequest $request)
    {
        $_username = $request->get('username');
        $_password = $request->get('password');

        if (Auth::attempt(['username' => $_username, 'password' => $_password])) {
            $_user = Auth::user();
            $_token = $_user->createToken('lost@found!22');

            return $this->successResponse([
                'status' => 1,
                'message' => 'Login Success',
                'token' => $_token->plainTextToken,
                'user' => $_user
            ]);

        } else {
          return $this->unAuthResponse([
              'status'=>0,
              'message'=>'Login Fail',
              'token'=>'',
              'user'=>''
          ]);
        }

    }
}
