<?php

	if(isset($_POST["access_token"])){
		//Check type of access token

		$response["success"]=1;
		$response["message"]="Server Connected";

	}
	else{
		$response["success"]=0;
		$response["message"]="Error connecting";		
	}

	print(json_encode($response));
?>