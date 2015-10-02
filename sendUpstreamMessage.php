<?php
	//Google cloud messaging GCM-API url
    
    require 'databaseSetup.php';

    $json = file_get_contents("php://input");
    $data_decode = json_decode($json);


    $data = $data_decode->{'data'};
    $reg_ids = $data_decode->{'ids'};


    $gcmRegID  = "dd9A907QS3g:APA91bFo1R5ebRHYUsiOjK4hzFzYfSZXdalZtjHzgVZoMuJoYZ-SLz4_SMWh2AEgcvZL1x-WL-pD0l4_q46fy7lh9hYna82a1amGRu4EpxxcNoM6zsDV4QonWDEuCrb0_F8ptPsVXx8C";
    $registration_ids=array($gcmRegID);

    $message = array(
        // "message_type" => $data->{'msg_type'},
        "inform_type" => $data->{'msg_type'},
        "msg_optional" => $data->{'msg_optional'},
        "date" => $data->{'date'},
        "time_from" => $data->{'time_from'},
        "time_to" => $data->{'time_to'},
        "venue" => $data->{'venue'},
        "subject" => $data->{'subject'},
        "from_id" => $data_decode->{'id'},
        "from_name" => $data_decode->{'name'},
        "action" => $data_decode->{'action'},
    );

    $query = "INSERT into extra_lecture_chats values ('$message')";
    $result = mysqli_query($conn, $query);

    // Update your Google Cloud Messaging API Key
    define("GOOGLE_API_KEY", "AIzaSyBO1EeoxA7GqZ8iRvQZEdeX2rtL9266Lts");        
    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $url = 'https://gcm-http.googleapis.com/gcm/send';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);   
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = "";
    foreach ($reg_ids as $reg_id) {

        $query = "select reg_id from roll_reg_no where roll_no='$reg_id'";
        $result_query = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result_query);

        $reg_id = $row["reg_id"];
        $registration_ids=array($reg_id);
        $result = $result + $reg_id;
        $fields = array(
            'registration_ids' => $registration_ids,
            'message_id' => 1,
            'data' => $message,		
            'delay_while_idle' => false,
            'time_to_live' => 0
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = $result + curl_exec($ch);				
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
    }
    return $result;
?>