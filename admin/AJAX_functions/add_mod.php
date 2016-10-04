<?php
	session_start();
	include("../admin_functions.php");
	if(check_admin_status($_SESSION['username']))
	{
		include("../db_con.php");
		
		if($_GET['type'] == 'mod')
		{
			$email = $_GET['email'];
			$email = mysqli_real_escape_string($con, $email);
			$q = "update Player set status = 4 where p_email = '$email'";
			$result = mysqli_query($con, $q) or die ("Error running query " . mysqli_error($con));
			if(mysqli_affected_rows($con) == 1)
				echo "Moderator " . $email . " added successfully.";
			else
				echo "Error adding moderator " . $email;
		}
		
		if($_GET['type'] == 'admin')
		{
			$email = $_GET['email'];
			$email = mysqli_real_escape_string($con, $email);
			$q = "update Player set admin = 1, status = 4 where p_email = '$email'";
			$result = mysqli_query($con, $q) or die ("Error running query " . mysqli_error($con));
			if(mysqli_affected_rows($con) == 1)
				echo "Admin " . $email . " added successfully.";
			else
				echo "Error adding admin " . $email . " (Probably already an admin.)";
		}
		
		if($_GET['type'] == 'remove')
		{
			$email = $_GET['email'];
			$email = mysqli_real_escape_string($con, $email);
			$q = "update Player set admin = 0, status = 3 where p_email = '$email'";
			$result = mysqli_query($con, $q) or die ("Error running query " . mysqli_error($con));
			if(mysqli_affected_rows($con) == 1)
				echo "Admin " . $email . " removed successfully.";
			else
				echo "Error removing admin " . $email . " (Probably not an admin.)";
		}
	}
?>