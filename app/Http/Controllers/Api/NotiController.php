<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


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


    public function test()
    {

        $deviceToken = "dhYO4BcCSyeA0qWpMHG9cv:APA91bGqJqCLBfbKHe9bYdG9ix0HNUhKqn0sLNhDyvQEw736raYCUQ4SJjMfT4nnnB6d_tyBazttwsY2PI-_Eh8tXmjfp7zQGBWuGHa4pz9cM-CcNA_c_mjrgvXzVrWxaRJ56mDemiTz";
        $title = 'My Notification Title';
        $body = 'My Notification Body';
        $imageUrl = 'http://lorempixel.com/400/200/';



        $message = CloudMessage::fromArray([
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ], // optional
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'item_id' => 1,
                'user_id' => 3,
                'item_name' =>
                "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
                'user_name' => 'User',
                'item_type' => 'Found',
                'item_create_time' => (string)date('Y-m-d H:i:s')
            ]
        ]);

        return $this->firebase->getMessaging()->send($message);
    }
}
