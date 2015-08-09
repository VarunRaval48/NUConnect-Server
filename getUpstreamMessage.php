<?php

	$json = file_get_contents('php://input');
	$obj = json_encode($json);

	print("Result from server "+$obj.data);
?>