<?php
	$url = curl_init("localhost:9000/nuconnect/sendResponse.php?id=1");

	curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);

	$resp = curl_exec($url);

	echo "<h1>Here {$resp}</h1>"
?>