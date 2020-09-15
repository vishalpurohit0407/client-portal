<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PushNotification;
use App\UserDevices;

class CronController extends Controller {
    
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
      
    }

    public function cron_send_push_notification(){
        
      $notificationData = PushNotification::where('status','=','0')->get();
      if($notificationData){
          $finalDeviceIDs = array('android'=>[],'ios'=>[], 'web'=>[]);
          
          $apiKey = env('GOOGLE_FCM_API_KEY');
          $GOOGLE_FCM_URL = 'https://fcm.googleapis.com/fcm/send';

          foreach($notificationData as $record) {

              $finalDeviceIDs['android'] = UserDevices::where('user_id',$record->user_id)->where('device_type','android')->pluck('device_token')->toArray();
              $finalDeviceIDs['ios'] = UserDevices::where('user_id',$record->user_id)->where('device_type','ios')->pluck('device_token')->toArray();
              $finalDeviceIDs['web'] = UserDevices::where('user_id',$record->user_id)->where('device_type','web')->pluck('device_token')->toArray();

              $record->status = '1';
              $record->update();

              $payload = $record->payload;
              $title = $record->title;
              $message = $record->message;

              if(count($finalDeviceIDs['ios'])) {

                $aps = array("title"=>$title, "is_background"=>false, "message"=>$message, "body"=>$message,"notification_type"=>"1","badge"=>"1", "payload"=>$payload);
             
                $fields = array(
                   'registration_ids'          => $finalDeviceIDs['ios'],
                   'priority'                  => "high",
                   'notification'              => $aps,
                   'data'                      => $aps,
                );

                $headers = array(
                   $GOOGLE_FCM_URL,
                   'Content-Type: application/json',
                   'Authorization: key=' . $apiKey
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $GOOGLE_FCM_URL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                $result = curl_exec($ch);
                curl_close($ch);
              }

              if(count($finalDeviceIDs['android'])) {

                $aps = array("title"=>$title, "is_background"=>false, "message"=>$message, "body"=>$message,"notification_type"=>"1","badge"=>"1", "payload"=>$payload);
             
                $fields = array(
                   'registration_ids'          => $finalDeviceIDs['android'],
                   'priority'                  => "high",
                   'notification'              => $aps,
                   'data'                      => $aps,
                );

                $headers = array(
                   $GOOGLE_FCM_URL,
                   'Content-Type: application/json',
                   'Authorization: key=' . $apiKey
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $GOOGLE_FCM_URL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                $result = curl_exec($ch);
                curl_close($ch);
              }

              if(count($finalDeviceIDs['web'])) {

                $fields = [
                    "to" => json_encode($finalDeviceIDs['web']),
                    "notification" =>
                    [
                        "title" => $title,
                        "body" => $message,
                        "icon" => asset('assets/media/logos/logo-letter-9.png'),
                        "click_action" => url('notification')
                    ],
                ];

                $headers = array(
                   $GOOGLE_FCM_URL,
                   'Content-Type: application/json',
                   'Authorization: key=' . $apiKey
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $GOOGLE_FCM_URL);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

                $result = curl_exec($ch);
                curl_close($ch);
              }
          }
      }

      echo "-- Completed"; exit;
    }
}