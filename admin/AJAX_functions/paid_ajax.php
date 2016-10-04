<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	if(check_admin_status($_SESSION['username']))
	{
		$email = $_POST['p_email'];
		$q = "update Player set paid = 1 where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("Error updating player " . mysqli_error($con));
		
		if(mysqli_affected_rows($con) == 1)
		{}
		else
			echo ("false");
	}
?>