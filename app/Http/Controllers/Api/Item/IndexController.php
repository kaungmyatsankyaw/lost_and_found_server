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
        }

        return Constant::successResponse($_item->makeHidden('location', 'updated_at', 'user'), 'Item Create Success', Constant::$_createdStatus);
    }


    /** Get All Items */
    public function getItems()
    {
        $_items = Item::orderBy('created_at', 'desc')->jsonPaginate()->makeHidden(['location', 'update', 'user']);
        return Constant::successResponse($_items, 'Item List', Constant::$_successStatus);
    }

    /** Delete Items */
    public function delete(Request $request)
    {
        $_item_id = $request->get('id');
        $_item = \DB::select("select count(*) as count from items where id=? and user_id=?", [$_item_id, $request->user()->id])[0]->count;

        if ($_item != 0) {
            Item::destroy($_item_id);
            return Constant::successResponse([], 'Item Delete Success', Constant::$_successStatus);
        } else {
            return Constant::failResponse([], 'Item Delete Fail', Constant::$_unauthorizedStatus);
        }
    }

    /** Get Location */
    public function getLocation(Request $request)
    {
        $_item_id = $request->get('id');

        $_query = "select ST_X(location) as latitude,ST_Y(location) as longitude from items where id =?";

        $_result = \DB::select($_query, [$_item_id])[0];

        if ($_result->latitude != null && $_result->longitude) {
            $_data['lat'] = $_result->latitude;
            $_data['long'] = $_result->longitude;
        } else {
            $_data['lat'] = 0;
            $_data['long'] = 0;
        }

        return Constant::successResponse($_data, 'Location For Item', Constant::$_successStatus);
    }

    /** Edit Item */
    public function editItem(Request $request)
    {

        $_user_id = $request->user()->id;
        $_item_id = $request->get('id');
        $_item_name = $request->get('name');
        $_item_type = $request->get('type');
        $_item_description = $request->get('description');
        $_item = $request->get('item');
        $_item_address = $request->get('address');
        $_item_location = $request->get('location');
        $_item_found_time = $request->get('time');

        ['lat' => $_lat, 'lng' => $_lng] = $_item_location;


        $_query = "update items set name=?,item=?,type=?,description=?,address=?,time =?,updated_at=?";

        if (!empty($_lat) && !empty($_lng)) {
            $_query .= ',location=' . \DB::raw("ST_GeomFromText('POINT(${_lat} ${_lng})')");
        }

        $_query .= ' where id=?';

        $_result = \DB::update($_query, [$_item_name, $_item, $_item_type, $_item_description, $_item_address, $_item_found_time, date('Y-m-d H:i:s'), $_item_id]);

        if ($_result != 0) {
            return Constant::successResponse([], 'Item Update Success', Constant::$_createdStatus);
        } else {
            return Constant::failResponse([], 'Item Update Fail', Constant::$_internalServerStatus);
        }
    }
}
