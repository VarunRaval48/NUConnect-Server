<?php
	require 'databaseSetup.php';

	if(isset($_POST["email"])){

		// $email = explode('@', $_POST["email"])[0];
		$email = $_POST["email"];
		$query = "SELECT * from user_access_token where email='$email'"; 
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			$query = "UPDATE user_access_token SET log_status='1', login_date=now() where email='$email'";
			$result = mysqli_query($conn, $query);
		}
	}
?>