<?php 
	$buffer = fsockopen("localhost", 8080);
	$output = fwrite($buffer, $_POST['data']);
	$closeOutput = fclose($buffer);
	echo $closeOutput;
?>