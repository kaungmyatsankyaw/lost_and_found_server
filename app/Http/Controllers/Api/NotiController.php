<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use DB;
use App\Models\Item;


class NotiController extends Controller
{

    public $firebase;

    public function __construct()
    {
        $this->firebase = $this->getConnectFB();
    }

    /********** Return Firebase *********/
    private function getConnectFB()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(base_path('noti-service.json'));

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();

        return $firebase;
    }


    public function notiSend(Request $request)
    {

        $_id = Item::select('id', 'type')->orderBy('id', 'desc')->limit(1)->get()[0];

        ['lat' => $_lat, 'lng' => $_lng] = $request->get('location');

        $_tokens = $this->getToken($request->user()->id, $_lat, $_lng);

        $title = 'New Item Added';
        $body =  $request->user()->name . ' add New Item near you';


        $message = CloudMessage::fromArray([

            'notification' => [
                'title' => $title,
                'body' => $body,
            ], // optional
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'item_id' => $_id->id,
                'user_id' => $request->user()->id,
                'item_name' => $request->user()->name . ' add New Item near you. Notify user when you find item . ',
                'user_name' => 'User',
                'item_type' => $_id->type == 1 ? 'Found' : 'Lost',
                'item_create_time' => (string)date('Y-m-d H:i:s')
            ]
        ]);

        $_resport = $this->firebase->getMessaging()->sendMulticast($message, $_tokens);
        return;
    }

    /** Get Token */
    private function getToken($_user_id, $_lat, $_lng)
    {
        $_query = "SELECT DISTINCT noti_tokens.token from ( select user_id,udid,zip.id,zip.name,st_x(zip.location) as lat,st_y(zip.location) as lng,
        IF(type = 1, 'Found', 'Lost') as type,
                111.045* DEGREES(ACOS(LEAST(1.0, COS(RADIANS(latpoint))
                * COS(RADIANS(st_x(zip.location)))
                * COS(RADIANS(longpoint) - RADIANS(st_y(zip.location)))
                + SIN(RADIANS(latpoint))
                * SIN(RADIANS(st_x(zip.location)))))) AS distance
        FROM  items zip
        left JOIN users on users.id = zip.user_id
        JOIN (
        SELECT  ?  AS latpoint,  ? AS longpoint,
        10.0 AS radius,      111.045 AS distance_unit
        ) AS p ON 1=1
        WHERE st_x(zip.location)
        BETWEEN p.latpoint  - (p.radius / p.distance_unit)
        AND p.latpoint  + (p.radius / p.distance_unit)
        AND st_y(zip.location)
        BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
        AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
         AND zip.user_id != ?                                                 
         ORDER BY distance ) as u left join noti_tokens on noti_tokens.udid = u.udid";

        $_result = DB::select($_query, [$_lat, $_lng, $_user_id]);

        foreach ($_result as $_re) {
            $deviceToken[] = $_re->token;
        }

        \Log::info(json_encode($deviceToken));
        return $deviceToken;
    }
}
