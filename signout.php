<?php

	if(isset($_POST["email"])){
		$email = $_POST["email"];

		require 'databaseSetup.php';

		$query = "UPDATE user_access_token set log_status='0', logout_date=now() where email='$email'";

		$result = mysqli_query($conn, $query);

		mysqli_close($conn);

		if($result){
			print("Sign out successful");
		}
		else{
			print("sign out Unsuccessful");
		}
	}
?>