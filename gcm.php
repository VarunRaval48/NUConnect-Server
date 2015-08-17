<?php
	//Generic php function to send GCM push notification
   function sendMessageThroughGCM($registatoin_ids, $message) {
		//Google cloud messaging GCM-API url
        $url = 'https://gcm-http.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'message_id' => 1,
            'data' => $message,		
            'delay_while_idle' => false,
            'time_to_live' => 0
        );
		// Update your Google Cloud Messaging API Key
		define("GOOGLE_API_KEY", "AIzaSyBO1EeoxA7GqZ8iRvQZEdeX2rtL9266Lts"); 		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);				
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
?>
<?php
	
	//Post message to GCM when submitted
	$pushStatus = "GCM Status Message will appear here";	
	if(!empty($_GET["push"])) {	
		$gcmRegID  = "dd9A907QS3g:APA91bFo1R5ebRHYUsiOjK4hzFzYfSZXdalZtjHzgVZoMuJoYZ-SLz4_SMWh2AEgcvZL1x-WL-pD0l4_q46fy7lh9hYna82a1amGRu4EpxxcNoM6zsDV4QonWDEuCrb0_F8ptPsVXx8C";
		$pushMessage = $_POST["message"];
		if (isset($gcmRegID) && isset($pushMessage)) {		
			$gcmRegIds = array($gcmRegID);
			$message = array("message" => $pushMessage);	
			$pushStatus = sendMessageThroughGCM($gcmRegIds, $message);
		}		
	}
	
	//Get Reg ID sent from Android App and store it in text file
	if(!empty($_GET["shareRegId"])) {
		$gcmRegID  = $_POST["regId"]; 
		file_put_contents("GCMRegId.txt",$gcmRegID);
		echo "Done!";
		exit;
	}	
?>
<html>
    <head>
        <title>Google Cloud Messaging (GCM) in PHP</title>
		<style>
			div#formdiv, p#status{
			text-align: center;
			background-color: #FFFFCC;
			border: 2px solid #FFCC00;
			padding: 10px;
			}
			textarea{
			border: 2px solid #FFCC00;
			margin-bottom: 10px;			
			text-align: center;
			padding: 10px;
			font-size: 25px;
			font-weight: bold;
			}
			input{
			background-color: #FFCC00;
			border: 5px solid #fff;
			padding: 10px;
			cursor: pointer;
			color: #fff;
			font-weight: bold;
			}
			 
		</style>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script>
		$(function(){
			$("textarea").val("");
		});
		function checkTextAreaLen(){
			var msgLength = $.trim($("textarea").val()).length;
			if(msgLength == 0){
				alert("Please enter message before hitting submit button");
				return false;
			}else{
				return true;
			}
		}
		</script>
    </head>
	<body>
		<div id="formdiv">
		<h1>Google Cloud Messaging (GCM) in PHP</h1>	
		<form method="post" action="/nuconnect/gcm.php/?push=true" onsubmit="return checkTextAreaLen()">					                                                      
				<textarea rows="5" name="message" cols="45" placeholder="Message to send via GCM"></textarea> <br/>
				<input type="submit"  value="Send Push Notification through GCM" />
		</form>
		</div>
		<p id="status">
		<?php echo $pushStatus; ?>
		</p>        
    </body>
</html>