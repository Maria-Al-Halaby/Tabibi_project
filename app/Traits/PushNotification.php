<?php

namespace App\Traits;

use Exception;
use Google\Auth\ApplicationDefaultCredentials;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


/* trait PushNotification
{
    public function sendNotification($token , $title , $body , $data = [])  
    {
        $fcmurl = "https://fcm.googleapis.com/v1/projects/tabby-3d3c4/messages:send";

        $notification = [
            'notification' => [
                'title' => $title , 
                'body' => $body , 
            ], 
            'data' => $data , 
            'token' => $token
        ];

        try{
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->GetAccessToken() , 
                'content-type' => 'application/json'
            ])->post($fcmurl , ["message" => $notification]);
            return $response->json();

            
        }catch(Exception $e)
        {
            Log::error("Error sending push notification to $token");
            return false;
        }
    }

    private function GetAccessToken()
    {
        $keyPath  =  config('services.firebase.key_path');
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . $keyPath);

        //define your scopes for your API call
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = ApplicationDefaultCredentials::getCredentials($scopes);

        $token = $credentials->fetchAuthToken();

        return $token['access_token'] ?? null ;
    }
} */


trait PushNotification
{
    public function sendNotification(string $token, string $title, string $body, array $data = []): bool|array
    {
        if (!$token) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('FCM access token not available');
            return false;
        }

        $fcmUrl = 'https://fcm.googleapis.com/v1/projects/tabby-3d3c4/messages:send';

        $message = [
            'notification' => [
                'title' => $title,
                'body'  => $body,
            ],
            'data'  => $data,
            'token' => $token,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ])->post($fcmUrl, [
                'message' => $message,
            ]);

            if (!$response->successful()) {
                Log::error('FCM send failed', $response->json());
                return false;
            }

            return $response->json();

        } catch (Exception $e) {
            Log::error('Error sending push notification', [
                'token' => $token,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function getAccessToken(): ?string
    {
        return Cache::remember('fcm_access_token', 50 * 60, function () {
            $keyPath = config('services.firebase.key_path');

            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $keyPath);

            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

            $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
            $token = $credentials->fetchAuthToken();

            return $token['access_token'] ?? null;
        });
    }
}