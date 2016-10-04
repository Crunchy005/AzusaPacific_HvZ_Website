<?php
	session_start();
	include("../admin_functions.php");
	
	if(check_admin_status($_SESSION['username']))
	{	
		include("../db_con.php");
		
		$mission_id = $_POST['id'];
		
		$q = "select * from Mission where mission_id = $mission_id";
		
		$result = mysqli_query($con, $q) or die ("Error getting Mission " . mysqli_error($con));
		$result_array = mysqli_fetch_assoc($result);
		
		echo($result_array['mission_email']);
	}
?>