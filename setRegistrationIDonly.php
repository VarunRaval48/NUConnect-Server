<?php
	require 'databaseSetup.php'
	if(isset($_POST["reg_id"]) && isset($_POST["email"])){

		$query = "SELECT * from user_access_token where email='$email'"; 
		$result = mysqli_query($conn, $query);

		$reg_id = $_POST["reg_id"];
		$email = $_POST["email"];

		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			if($row["reg_id"]!=$_POST["reg_id"]){
				$query = "UPDATE user_access_token SET reg_id='$reg_id' log_status='1', login_date=now() where email='$email'";
				$result = mysqli_query($conn, $query);;
			}
		}
		else{		//Not Necessary
			$query = "INSERT into user_access_token (access_token, reg_id, email, log_status, login_date) values
			 	('$access_token', '$reg_id', '$email', '1', now())";
			$result = mysqli_query($conn, $query);
		}
	}
?>