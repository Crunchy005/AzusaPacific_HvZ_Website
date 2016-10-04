<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	
	if(check_admin_status($_SESSION['username']))
	{	
		$shares = $_GET['shares'];
		$q = "update Game set shares = $shares";
		$result = mysqli_query($con, $q) or die ("Error updating shares " . mysqli_error($con));
		
		if(mysqli_affected_rows($con) == 1)
			echo $shares;
		else
			echo "Error";
	}
	
?>