<?php
	//Google cloud messaging GCM-API url
    
    require 'databaseSetup.php';

    $json = file_get_contents("php://input");
    $data_decode = json_decode($json);


    $data = $data_decode->{'data'};
    $to_ids = $data_decode->{'ids'};
    $to_ids_group = $data_decode->{'ids_group'};


    $gcmRegID  = "dd9A907QS3g:APA91bFo1R5ebRHYUsiOjK4hzFzYfSZXdalZtjHzgVZoMuJoYZ-SLz4_SMWh2AEgcvZL1x-WL-pD0l4_q46fy7lh9hYna82a1amGRu4EpxxcNoM6zsDV4QonWDEuCrb0_F8ptPsVXx8C";
    $registration_ids=array($gcmRegID);

    $from_id = $data_decode->{'id'};
    $from_name = $data_decode->{'name'};
    $action = $data_decode->{'action'};
    $date_sent_on = $data_decode->{'date_sent_on'};
    $msg_type = $data_decode->{'msg_type'};
    // $query = "SELECT msg_id from message_details_extralecture where from_id='$from_id'";
    $query = "SELECT msg_id from message_details_extralecture where from_id='$from_id' order by msg_id desc limit 1";
    $result_query = mysqli_query($conn, $query);
    if(mysqli_num_rows($result_query)>0){
        $row = mysqli_fetch_assoc($result_query);
        $msg_id = $row["msg_id"] + 1;
    }
    else{
        $msg_id = 1;
    }

    //Single level of tree in json message sent
    $message = array(
        // "message_type" => $data->{'msg_type'},
        "msg_id" => $msg_id,
        "msg_optional" => $data->{'msg_optional'},
        "date" => $data->{'d'},
        "time_from" => $data->{'t_f'},
        "time_to" => $data->{'t_t'},
        "venue" => $data->{'v'},
        "subject" => $data->{'s'},
        "from_id" => $from_id,
        "from_name" => $from_name,
        "action" => $action,
        "date_sent_on" => $date_sent_on,
        "msg_type" => $msg_type,
    );

    $data = json_encode($data);
    $query = "INSERT into message_details_extralecture (msg_id, from_id, from_name, msg_type, data, date_sent_on) 
        values ($msg_id , '$from_id', '$from_name', '$msg_type', '$data', '$date_sent_on')";
    mysqli_query($conn, $query);


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

    //TODO Insert msg_id to receipents_extralecture
    foreach ($to_ids_group as $to_id_group) {
        $query = "INSERT into receipents_extralecture values ('$msg_id', 'to_id')";
        mysqli_query($conn, $query);
    }

    foreach ($to_ids as $to_id) {

        $query = "INSERT into receipents_extralecture values ('$msg_id', '$to_id')";
        mysqli_query($conn, $query);

        $query = "select reg_id from roll_reg_no where roll_no='$to_id'";
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
    mysqli_close($conn);
    
    return $result;
?>