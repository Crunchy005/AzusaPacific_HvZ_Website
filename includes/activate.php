<?php
	$email = mysqli_real_escape_string($_GET['user']);
	$code = mysqli_real_escape_string($_GET['code']);
	$q = "select * from Player where p_email='$email' and kill_code='$code'";
	$result = mysqli_query($q, $con) or die ("unable to run query: ". mysqli_error());
	if(mysqli_num_rows($result) == 1)
	{
		$q = "update Player set active=1 where p_email='$email'";
		mysqli_query($q, $con) or die ("unable to run query: ".mysqli_error());
		echo("<h1>Your account is Now Active!!</h1>");
	}
	else
		echo("<h1>Something went wrong :(</h1>");		
?>