<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	
	if(check_admin_status($_SESSION['username']))
	{
		$current_date = date("Y-m-d");
		$email = $_POST['p_email'];
		$q = "update Player set vaccinated=1 where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("error updating databsse " . mysqli_error($con));
		if($result)
		{
			echo trim($email);
		}
		else
			echo "false";
			
		$q = "insert into Vaccination (pid, vaccination_date) values ((select pid from Player where p_email = '$email'), '$current_date')";
		$result = mysqli_query($con, $q) or die ("error inserting record " . mysqli_error($con));
	}
?>