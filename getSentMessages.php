<?php
	
	require 'databaseSetup.php';

	$result = array();
	if(isset($_POST["last_sent_message"])){

		$email = $_POST["email"];
		$last_sent_message = $_POST["last_sent_message"];

		$query = "SELECT * from message_details_extralecture where from_id='$email' and msg_id>'$last_sent_message'";
		$result_query = mysqli_query($conn, $query);

		if(mysqli_num_rows($result_query)>0){
			while($row = mysqli_fetch_assoc($result_query)){
				$data = $row["data"];
				$data = json_decode($data);
				$data_array = array(
					'msg_optional'=>$data->{'msg_optional'},
					'msg_type'=>$data->{'msg_type'},
					'd'=>$data->{'d'},
					't_f'=>$data->{'t_f'},
					't_t'=>$data->{'t_t'},
					'v'=>$data->{'v'},
					's'=>$data->{'s'},
				);
				$array = array(
					'm_i'=>$row["msg_id"],
					'f_i'=>$row["from_id"],
					'f_n'=>$row["from_name"],
					'd_s'=>$row["date_sent_on"],
					'data'=>$data_array,
				);
				array_push($result, $array);
			}
			print(json_encode($result));
		}
	}
?>