<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	
	if(check_admin_status($_SESSION['username']))
	{
		$email_reveal = $_GET['email'];
		$q = "update Player set status = 2 where p_email = '$email_reveal'";
		$result = mysqli_query($con, $q) or die ("error updating " . mysqli_error($con));
		if(mysqli_affected_rows($con) == 1)
			echo "Revealed Alpha";
		else
			echo "Failed to Reveal";
	}
?>