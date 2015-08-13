<?php

	require 'databaseSetup.php';

	if(isset($_POST["reg_id"]) && isset($_POST["email"]) && isset($_POST["access_token"])){
		
		$reg_id = $_POST["reg_id"];
		$email = $_POST["email"];
		$access_token = $_POST["access_token"];


		$query = "SELECT * from user_access_token where email='$email'"; 
		$result = mysqli_query($conn, $query);

		$str="";
		$flag=0;
		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			if($row["access_token"]!=$access_token || $row["reg_id"]!=$reg_id){
				$query = "UPDATE user_access_token SET access_token='$access_token', reg_id='$reg_id', log_status='1', login_date=now() where email='$email'";
				$result = mysqli_query($conn, $query);$flag=1;
			}
			// if($row["reg_id"]!=$_POST["reg_id"]){
			// 	$query = "UPDATE user_access_token SET reg_id='$reg_id' log_status='1', login_date=now() where email='$email'";
			// 	$result = mysqli_query($conn, $query);$flag=1;
			// }
			if($flag==0){
				$query = "UPDATE user_access_token SET log_status='1', login_date=now() where email='$email'";
				$result = mysqli_query($conn, $query);				
			}
		}
		else{
			$query = "INSERT into user_access_token (access_token, reg_id, email, log_status, login_date) values
			 	('$access_token', '$reg_id', '$email', '1', now())";
			$result = mysqli_query($conn, $query);
		}

		mysqli_close($conn);

		if($result){
			print("Query Ran successfully");
		}
		else{
			print("Error Running Query");
		}
	}
	else{

		print "Problem with post request";
	}
?>