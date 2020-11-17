<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\Item;
use App\Models\User;
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
                'user' => $_user->id
            ]);
        } else {
            return $this->unAuthResponse([
                'status' => 0,
                'message' => 'Login Fail',
                'token' => '',
                'user' => ''
            ]);
        }
    }

    /** Register
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function register(RegisterRequest $request)
    {
        $_lat = $request->get('lat');
        $_lng = $request->get('lng');

        $request['password'] = bcrypt($request['password']);

        $_user = User::create($request->except(['lat,lng']));

        if (!empty($_lat) && !empty($_lng)) {
            $_user->location = \DB::raw("ST_GeomFromText('POINT(${_lat} ${_lng})')");
            $_user->save();
        }

        return $this->successResponse([
            'status' => 1,
            'message' => 'Register Success',
            'user' => $_user->id
        ]);
    }

    /** Get Profile
     * @param Request $request
     */
    public function profile(Request $request)
    {

        $_user = $request->user()->makeHidden(['items']);

        if (!empty($_user->location)) {
            $_location = \DB::select("SELECT ST_X(location) as lat,ST_Y(location) as lng FROM users where id=?;", [$request->user()->id]);
        } else {
            $_location = [
                'lat' => '',
                'lng' => ''
            ];
        }

        $_user->item_count = $_user->items->makeHidden(['location'])->count();
        $_user->location = $_location;
        return Constant::successResponse($_user, 'Success', 200);
    }

    public function items(Request $request)
    {
        $_items = Item::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get()->makeHidden(['location', 'created_at', 'user']);
        return Constant::successResponse($_items, 'User Item List', Constant::$_successStatus);;
    }
}

//
//         $_query = "select ST_Latitude(ST_GeomFromText('Point(${_lat} ${_lng})',4326, 'axis-order=lat-long'))  AS `lat_lotus_temple`,
//    ST_Longitude(ST_GeomFromText('Point(${_lat} ${_lng})',4326)) AS `long_lotus_temple` from users where id =6";
//        return \DB:: select($_query);

//        $_user->location = [
//            'lat' => empty($_lat) ? '' : $_lat,
//            'lng' => empty($_lng) ? '' : $_lng
//        ];
