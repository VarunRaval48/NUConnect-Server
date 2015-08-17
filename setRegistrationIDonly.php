<?php
	require 'databaseSetup.php'
	if(isset($_POST["reg_id"]) && isset($_POST["email"])){

		$reg_id = $_POST["reg_id"];
		$email = explode('@', $_POST["email"])[0];

		$query = "SELECT * from roll_reg_no where roll_no='$email'"; 
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			if($row["reg_id"]!=$reg_id){
				$query = "UPDATE roll_reg_no SET reg_id='$reg_id', where roll_no='$email'";
				$result = mysqli_query($conn, $query);;
			}
		}
		// else{		//Not Necessary
		// 	$query = "INSERT into user_access_token (access_token, reg_id, email, log_status, login_date) values
		// 	 	('$access_token', '$reg_id', '$email', '1', now())";
		// 	$result = mysqli_query($conn, $query);
		// }
	}
?>