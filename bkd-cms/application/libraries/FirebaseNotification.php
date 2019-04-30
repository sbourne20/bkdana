<?php defined('BASEPATH') OR exit('No direct script access allowed');

class FirebaseNotification {

	public function notif_aprrove($token, $message, $title, $action){

		$json_data = array(
			"to" => $token,
			"collapse_key" => "type_a",
		    "data" => array(
		       "body" => $message,
		    "title"=> $title,
		    "key_1" => "Value for key_1",
		    "click_action" => $action
		    )
		);

		$data = json_encode($json_data);
		
		$url = 'https://fcm.googleapis.com/fcm/send';
		$server_key = 'AAAAYi5kAlc:APA91bHR0fLUhqWiI4_hZf3y0ciMnE8coHh03GsVKvoL9zjD0dz4PvXn-MxQbu7Pr29ryQvg122cloxw2l_jOALVL09tuBS6uZxv2393yz8y7iPgjhxjwZ36K08jHafrX0h2TIEMpxNJ';
		$headers = array(
		    'Content-Type:application/json',
		    'Authorization:key='.$server_key
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		if ($result === FALSE) {
		    die('Oops! FCM Send Error: ' . curl_error($ch));
		}
		curl_close($ch);


	}
}