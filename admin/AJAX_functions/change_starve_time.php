<?php
	session_start();
	include("../admin_functions.php");
	
	if(check_admin_status($_SESSION['username']))
	{	
		include("../db_con.php");
		
		$starve_time = $_GET['time'];
		if($starve_time == "")
			echo "<span style='color:red;'>Please enter a starve time from 1-99</span>";
		else
		{
			$q = "update Game set death_time_length=$starve_time";//create query
			$result = mysqli_query($con, $q) or die ("Mysqli error " . mysqli_error($con));
			if($result)
				echo $starve_time;
			else
				echo "error changing starve time";
		}
	}
	
?>