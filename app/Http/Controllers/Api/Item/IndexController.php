<?php

namespace App\Http\Controllers\Api\Item;

use App\Http\Controllers\Api\Constant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Item\CreateRequest;
use App\Models\Item;
use Illuminate\Http\Request;


class IndexController extends Controller
{

    /** Create Items
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $_user_id = $request->user()->id;

        $_item_name = $request->get('name');
        $_item_type = $request->get('type');
        $_item_description = $request->get('description');
        $_item = $request->get('item');
        $_item_address = $request->get('address');
        $_item_location = $request->get('location');
        $_item_found_time = $request->get('time');
//        $_item_phone = $request->get('phone');

        ['lat' => $_lat, 'lng' => $_lng] = $_item_location;

        $_item = Item::create([
            'user_id' => $_user_id,
            'name' => $_item_name,
            'contact_phone' => $request->user()->phone,
            'item' => $_item,
            'type' => $_item_type,
            'description' => $_item_description,
            'address' => $_item_address,
            'time' => $_item_found_time
        ]);

        if (!empty($_lat) && !empty($_lng)) {
            $_item->location = \DB::raw("ST_GeomFromText('POINT(${_lat} ${_lng})')");
            $_item->save();

//            $_item->location = $_item_location;
        }

        return Constant::successResponse($_item->makeHidden('location', 'updated_at', 'user'), 'Item Create Success', Constant::$_createdStatus);

    }

    public function getItems()
    {
        $_items = Item::orderBy('created_at', 'desc')->jsonPaginate()->makeHidden(['location', 'update', 'user']);
        return Constant::successResponse($_items, 'Item List', Constant::$_successStatus);

    }
}
