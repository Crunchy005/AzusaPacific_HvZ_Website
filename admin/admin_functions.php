<?php
	/* Ends the game by killing all the players that are still alive.  Zombie or human. */
	function end_game()
	{
		$date = date("Y-m-d");
		$q = "update Game set end_date = $date";
		$result = mysqli_query($con, $q) or die ("Error setting end date " . mysqli_error($con));
		
		/*$q = "Update Player set status = 1 where status = 5 or status = 3 or status = 2";
		$result = mysqli_query($con, $q) or die ("Error setting status " . mysqli_error($con));*/
		
		$q = "select p_email from Player where status = 5 or status = 3 or status = 2";
		$result = mysqli_query($con, $q) or die ("Error with query" . mysql_error($con));

		while($result_array = mysqli_fetch_array($result))//loop through joined table results
		{
			kill_player($result_array['p_email'], "The Game ended");
		}
	}
	//kills player and sends them an email with a reason they were killed.  This is only for admin stuff.
	function kill_player($email, $reason)
	{
		$q = "select pid from Player where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("Error with query" . mysql_error($con));

		$result_array = mysqli_fetch_assoc($result);
		
		$q = "update Player set status=1 where pid=".$result_array['pid'];//update player status to dead(1).
		mysqli_query($con, $q) or die ("Error setting status to 1 " . mysqli_error($con));
		$q = "delete from Death_timer where pid=" . $result_array['pid'];
		mysqli_query($con, $q) or die ("Error deleting from death timer " . mysqli_error($con));
		
		$header = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset=ISO-8859-1\r\n' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
		//send email to player to notify them that they are dead.
		$to = $email;
		$subject = "Thank you for playing. APUHVZ - " . date("Y");
		$message = "Thank you for playing APU HVZ, you are no longer in the game because $reason";
		$from = "apu.hvz@gmail.com";
		mail($to, $subject, $message, $header);
	}
	
	/* Grabs the number of shares */
	function get_shares()
	{
		include("db_con.php");
		
		$q = "Select shares from Game";
		$result = mysqli_query($con, $q) or die ("error getting shares" . mysqli_error($con));
		
		$shares = mysqli_fetch_assoc($result);
		
		return $shares['shares'];
		
	}
	
	//checks the admin status of an account and returns true or false.
	function check_admin_status($uname)
	{		
		include("db_con.php");
		
		$uname = mysqli_real_escape_string($con, $uname);//escape username
		$pass = mysqli_real_escape_string($con, $pass);//escape password
		
		$result = mysqli_query($con, "select admin from Player where p_email='$uname'") or die ("Mysql query error" . mysqli_error($con));
		$row = mysqli_fetch_array($result);//get row
		if(mysqli_num_rows($result) > 0)
		{
			if($row['admin'] == 1)//if admin is true return true
				return true;
			else
				return false;
		}
		else
			echo("No rows found for " . $uname . " " . $pass);
	}
	
	function delete_player($email)
	{
		include("db_con.php");
		
		$q = "select pid, f_name, l_name from Player where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("error getting player info " . mysqli_error($con));
		$result_array = mysqli_fetch_assoc($result);
		
		$pid = $result_array['pid'];
		
		mysqli_query($con, "delete from Player where pid = '$pid'") or die ("error removing player " . mysqli_error($con));
		mysqli_query($con, "delete from Death_timer where pid = '$pid'") or die ("error removing timer " . mysqli_error($con));
		foreach(glob("../player_pics/" . strtolower($result_array['f_name']) . strtolower($result_array['l_name']) . ".*") as $file)
		unlink($file);
	}
	
	/* Checks the registration open/closed status and returns result. */
	function get_reg_status()
	{
		include("db_con.php");
		
		$result = mysqli_query($con, "select registration from Game") or die ("Query error " . mysqli_error($con));//get registration info from Game
		$result = mysqli_fetch_array($result);
		if($result['registration'] == 'OPEN')//return results.
			return "OPEN";
		else
			return "CLOSE";
	}
	
	/* Gets game data. */
	function get_game_info()
	{
		include("db_con.php");
		
		$result = mysqli_query($con, "select * from Game") or die ("Query error " . mysqli_error($con));//get game info
		return mysqli_fetch_array($result);//return row array
	}
	
	/* Opens/Closes registration */
	function change_reg_status($status)
	{
		include("db_con.php");
		
		$result = mysqli_query($con, "update Game set registration='$status'");
		if($result)
			return true;
		else
			return false;
	}
	
	/* Grabs starve time length from game data. */
	function get_starve_time()
	{		
		$game_info = get_game_info();
		return $game_info['death_time_length'];
	}
	
	 /* This is called when a new game is created.  It clears the database and initializes a new game. */
	function create_game($email, $pass)
	{
		include("db_con.php");
		
		$default_account = mysqli_real_escape_string($con, $email);
		$default_password = mysqli_real_escape_string($con, $pass);
		
		if($default_account != "" && $default_password != "")
		{
			$backupFile = $dbname . date("Y-m-d-H-i-s") . '.gz';
			$command = "mysqldump --opt -h $hostname -u $username -p $password $dbname | gzip > $backupFile";
			system($command);

			//clear all the tables
			mysqli_query($con, "delete from Player") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Mission") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Game") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Mission_att") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "alter table Mission AUTO_INCREMENT = 1") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Tagged") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Vaccination") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from Death_timer") or die ("Cannot run query " . mysqli_error($con));
			mysqli_query($con, "delete from map") or die ("Cannot run query " . mysqli_error($con));
			//tables cleared at this point
			
			
			$password = password_hash($default_password, PASSWORD_BCRYPT);//default password
			$q = "insert into Player (f_name, l_name, pid, p_email, p_password, living_area, alpha, picture_location, kill_code, status, paid, active, admin) values ('APU', 'HVZ', 000001, '$default_account', '$password', 'Cougar Dome', 0, 'player_pics/APUHVZ.jpg', 111111, 4, 1, 1, 1)";//big insert statement for default account
			$result = mysqli_query($con, $q) or die ("Cannot run query " . mysqli_error($con));
			if(!$result)//if query is bad error
				return "error creating default account please talk to server administrator to fix this problem.";
			else//insert a row into game table for game settings.
			{
				$start_date = Date("Y-m-d");
				$result = mysqli_query($con, "insert into Game (game_id, registration, start_date, death_time_length) values (1, 'OPEN', '$start_date', 24)")  or die ("Cannot run query " . mysqli_error($con)); //create new game here
				if(!$result)
					return "error creating new game";
				else
					return true;
			}
			
			$files = glob("/player_pics/*"); //grab all player pictures
			foreach($files as $file) //iterate through pictures and delete
			{
				if(is_file($file)) //if file exists delete it
					unlink($file); //delete file
			}
		}
		else
			return "Please enter an email";
	}
	
	
	/* Select alpha zombie, if 1 is passed in it sets a random alpha, if you pass in an email it sets the alpha to the chosen email. */
	function select_alpha($option)
	{
		include("db_con.php");
		
		if($option == 1)
		{
			$q = "select * from Player where status = 3 and alpha = 1";//query database for all human players
			$result = mysqli_query($con, $q) or die ("Error getting players " . mysqli_error($con));
			for($result_array = array(); $temp = mysqli_fetch_assoc($result);)
			{
				$result_array[] = $temp['p_email'];
			}
			
			shuffle($result_array);
			
			$selected_player = $result_array[0];
			
			$q = "update Player set status = 5 where p_email = '$selected_player' and alpha = 1";
			$result = mysqli_query($con, $q) or die ("Error updating player " . mysqli_error($con));
			
			if(mysqli_affected_rows($con) == 1)
			{
				echo "<span style='color:red;'>Random alpha selected</span>";
				
				//Send alpha email
				$to = $selected_player;
				$emailHeader = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset: utf8\r\n' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
				$subject = "You are the Alpha!!";
				$message = "<h3>You are now the Alpha Zombie.</h3>  It is your job to get as many tags as you can.  Right now you will be seen as a human and you may continue wearing your bandana on your arm.  Happy Hunting!!!<br><br>-APU HVZ Mods " . date("Y");
				mail($to, $subject, $message, $emailHeader);
			}
			else
				echo "Error selecting a random alpha.";
		}
		else
		{
			$q = "update Player set status = 5 where p_email = '" . mysqli_real_escape_string($con, $option) . "' and alpha = 1";//query to update database
			$result = mysqli_query($con, $q);//run query
			if(mysqli_affected_rows($con) == 1)
			{
				echo "<span style='color:red;'>Updated Player as alpha</span>";//give feedback
				
				//Send alpha email
				$to = $option;
				$emailHeader = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset: utf8\r\n' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
				$subject = "You are the Alpha!!";
				$message = "<h3>You are now the Alpha Zombie.</h3>  It is your job to get as many tags as you can.  Right now you will be seen as a human and you may continue wearing your bandana on your arm.  Happy Hunting!!!<br><br>-APU HVZ Mods " + date("Y");
				mail($to, $subject, $message, $emailHeader);
			}
			else
				echo "<span style='color:red;'>No such player or player is already alpha.</span>";
		}
	}
	
	/* This function gets the players with alpha status is 5 and returns an array with all the players info */
	function get_alphas()
	{
		include("db_con.php");//include database connection file
		
		$q = "select * from Player where status = 5";//select all alpha status players
		$result = mysqli_query($con, $q) or die ("Error getting alpha list " . mysqli_error($con));
		$i = 0;//index
		while($temp = mysqli_fetch_assoc($result))//populate array
		{
			$result_array[$i] = $temp;
			$i++;//increment index
		}
		return $result_array;//return array
	}
	
?>