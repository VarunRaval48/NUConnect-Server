<?php

	//Not in USE

	if(isset($_POST["access_token"]) && isset($_POST["email"])){

		$access_token = $_POST["access_token"];
		$email = explode('@', $_POST["email"])[0];


		require 'databaseSetup.php';

		$query = "SELECT * from user_access_token where email='$email'";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result)>0){
			$row = mysqli_fetch_assoc($result);
			if($row["access_token"] != $access_token){
				$query = "UPDATE user_access_token SET access_token='$access_token', log_status='1', login_date=now() where email='$email'";

				$result = mysqli_query($conn, $query);
			}
		}
		else{
			$query = "INSERT into user_access_token (email, access_token, log_status, login_date) VALUES ('$email', '$access_token', '1', now())";

			$result = mysqli_query($conn, $query);
		}
		mysqli_close($conn);			

		if($result){
			$response["success"]=1;
			$response["message"]="Entry made Successfully";
		}
		else{
			$response["success"]=0;
			$response["message"]="An error occured";
		}		
	}
	else{
		$response["success"]=0;
		$response["message"]="Post values not set";
	}

	print(json_encode($response));


?>