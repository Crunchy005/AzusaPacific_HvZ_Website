<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");//include databse connection
	
	if(check_admin_status($_SESSION['username']))
	{
		$q = "select pid, p_email from Player where vaccinated = 0 and status = 3";
		$result = mysqli_query($con, $q) or die ("Error getting population " . mysqli_error($con));
		
		//loop through results
		while($result_array = mysqli_fetch_assoc($result))
		{
			//kill player and set status = 2
			$pid = $result_array['pid'];
			$q = "update Player set status = 2 where pid = $pid";
			mysqli_query($con, $q) or die ("Error updating status " . mysqli_error($con));
			
			//create time for killed player
			$current_time = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
			$q = "insert into Death_timer (pid, last_feed) values ($pid, $current_time)";
			mysqli_query($con, $q) or die ("Error adding timer " . mysqli_error($con));
			
			//send email to player
			$to = $result_array['p_email'];
			$subject = "Welcome to the Horde!";
			$message = "Welcome to the Zombie Horde.  You have been infected and are now a Zombie.  This is not the end of the game though, Playing as a zombie is just as rewarding as being a human.  So get out there and feast on the humans brainssss!";
			$headers = "From: apu.hvz@gmail.com";
			mail($to, $subject, $message, $headers);
		}
			
		$q = "update Player set vaccinated = 0";
		$result = mysqli_query($con, $q) or die ("Error cleansing " . mysqli_error($con));
		
		if($result)
			echo 1;
		else
			echo 0;
	}
?>