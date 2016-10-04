<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	
	if(check_admin_status($_SESSION['username']))
	{
		$currentTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$q = "update Death_timer set last_feed = $currentTime";
		
		mysqli_query($con, $q) or die ("Error updating database " . mysqli_error($con));
		
		echo "Updated all Timers!";
	}
?>