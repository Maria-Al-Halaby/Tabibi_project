<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\PushNotification;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    use PushNotification;

    public function update(Request $request)
    {
        $data = $request->validate([
            'fcm_token' => ['required', 'string', 'max:4096'],
        ]);

        $user = $request->user();
        $user->update([
            'fcm_token' => $data['fcm_token'],
        ]);

        return response()->json([
            'message' => 'FCM token updated successfully',
            'status' => true,
            'data' => [
                'fcm_token' => $user->fcm_token,
            ],
        ], 200);
    }

/*     public function send_notification()
    {
        $deviceToken = FirebaseMessaging.getInstance().getToken().addOnCompleteListener(task -> { if(task.isSuccessful()) {$token = task.getResult(); Log.d("FCM Token", $token); }});


        $title = "notification type";

        $body = "this is body message" ;

        $data = [
            'key1' => 'value1' , 
            'key2' => 'value2'
        ];

        $response = $this->sendNotification($deviceToken , $title , $body , $data);

        return response()->json(["message" => true , 'response' => $response] , 200);
    } */
}
