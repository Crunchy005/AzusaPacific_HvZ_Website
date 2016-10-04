<?php
	/*
	 * Changes the Players password and stores the new password in the database.	
	*/
	function get_con()
	{
		//Variables for connecting to your database.
		//These variable values come from your hosting account.
		$hostname = "localhost";
		$username = "APU_HVZ";
		$dbname = "APUHVZ";

		//These variable values need to be changed by you before deploying
		$password = "Zombonie1!";
			
		//Connecting to your database
		$con = mysqli_connect($hostname, $username, $password) OR DIE ("Unable to 
		connect to database! Please try again later.");
		mysqli_select_db($GLOBALS['con'], $dbname);
	}
	
	function update_user_pass($user, $oldPass, $newPass, $confirm)
	{
		$q = "select p_password from Player where p_email = '$user'";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("error getting player info " . mysqli_error($GLOBALS['con']));
		
		if(mysqli_num_rows($result) > 0)
		{
			$result_array = mysqli_fetch_assoc($result);
			
			
			if(password_verify($oldPass, $result_array['p_password']))
			{
				if($newPass == $confirm && preg_match("/^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{8,}$/", $newPass))
				{
					$hashedNew = password_hash($newPass, PASSWORD_BCRYPT);
					$q = "update Player set p_password = '$hashedNew' where p_email = '$user'";
					$result = mysqli_query($GLOBALS['con'], $q) or die ("error updating password " . mysqli_error($GLOBALS['con']));
					
					if($result == 1)
					{
						return true;
					}
					else
					{
						echo("not updated correctly");
						return false;
					}
				}
				else
				{
					echo("The passwords don't match or did not meet the minimum requirements<br>
						1 Uppercase, 1 Lowercase, 1 Number, and at least 8 Characters");
					return false;
				}
			}
			else
			{
				echo("old password doesn't match");
				return false;
			}
		}
		else
		{
			echo("user doesn't exsist(I don't know why.)");
			return false;
		}
	}
	
	/* Updates the game and checks zombie timers for dead zombies.  If dead zombies are found it changes their status to dead. */
	function update_game_status()
	{
		$q = "select * from Game";//get game info from database
		$result_game = mysqli_query($GLOBALS['con'], $q) or die ("Error with query" . mysqli_error($GLOBALS['con']));
		$game_info = mysqli_fetch_array($result_game);//get query results as array

		$q = "select p.pid, d.last_feed, p.p_email from Player p join Death_timer d on p.pid=d.pid where status = 2";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query" . mysqli_error($GLOBALS['con']));

		while($result_array = mysqli_fetch_array($result))//loop through joined table results
		{
			$current_date = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));//get current date
			$time_left = $current_date - $result_array['last_feed'];//get the difference in time
			$hours = floor(($time_left/60)/60);//convert to hours
			if($hours >= $game_info['death_time_length'])//if hours are greater than the time set in the game settings, kill the player.
			{
				$q = "update Player set status=1 where pid=".$result_array['pid'];//update player status to dead(1) if they are out of time.
				mysqli_query($GLOBALS['con'], $q) or die ("Error setting status to 1 " . mysqli_error($GLOBALS['con']));
				$q = "delete from Death_timer where pid=" . $result_array['pid'];
				mysqli_query($GLOBALS['con'], $q) or die ("Error deleting from death timer " . mysqli_error($GLOBALS['con']));
				
				//send email to player to notify them that they are dead.
				$to = $result_array['p_email'];
				$subject = "Thank you for playing. APUHVZ - " . date("Y");
				$message = "Thank you for playing APU HVZ, you did not get any kills within " . $game_info['death_time_length'] . " hours and have been removed from the game. -APU HVZ Staff";
				$from = "apu.hvz@gmail.com";
				//$emailHeader variable in site header.
				mail($to, $subject, $message, $emailHeader);
			}
		}
	}

	/* This checks the validity of code entered and gives credit to shares to update zombie timers */
	function enter_kill($kill_code, $shares)
	{
		$kill_code = trim($kill_code);
		$kill_code = strtoupper($kill_code);
		update_game_status();//updates the game before entering a kill

		$tagging_player_info = get_player_info($_SESSION['username']);//get tagging player info from session
		$kill_code = mysqli_real_escape_string($GLOBALS['con'], $kill_code);//make safe the kill code entered
		$q = "select * from Player where kill_code = '$kill_code' and status = 3";//create query
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));//send query to server
		
		if($tagging_player_info['status'] == 2 || $tagging_player_info['status'] == 5)
		{
			if(mysqli_num_rows($result) == 1)
			{
				$tagged_by_pid = $tagging_player_info['pid'];//player who made the tag
				$tagged_player_info = mysqli_fetch_array($result);//player that is killed info
				$tagged_player_pid = $tagged_player_info['pid'];//killed player pid
				$date = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));//current time

				$q = "update Player set status = 2 where kill_code = '$kill_code'";//update killed player status query
				$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with update " . mysqli_error($GLOBALS['con']));//update player status
				if($result)
				{
					//update feed time on shared emails
					foreach($shares as $share)//loop through shares array
					{
						$share = trim($share);//trim email
						if(strlen($share) > 7)//if the share is not empty and has at least enough chars for apu\.edu, check for validity
						{
							$share = mysqli_real_escape_string($GLOBALS['con'], $share);//make safe for sql
							$q = "select pid from Player where p_email = '$share' and status = 2";//make query
							$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with getting pid " . mysqli_error($GLOBALS['con']));//run query
							if(mysqli_num_rows($result) == 1)//if the player exsists update death_timer
							{
								$result_array = mysqli_fetch_assoc($result);
								$q = "update Death_timer set last_feed='$date' where pid=" . $result_array['pid'];//update timer
								mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
							}
							else
							{
								$q = "update Player set status = 3 where kill_code = '$kill_code'";//update killed player status query
								$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with update " . mysqli_error($GLOBALS['con']));//update player status
								return "<span style='color:red;'>Error with one of the shares, check the emails you are sharing to.</span>";//if one of the shares is bad undo the status update and return error
							}
						}
					}
					
					$q = "insert into Tagged (pid, taggedby_pid, tagged_date) values ($tagged_player_pid, $tagged_by_pid, '" . date("D F j h:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))) . "')";//insert query
					
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));//insert record into tagged table
					$q = "insert into Death_timer (pid, last_feed) values ($tagged_player_pid, '$date')";//insert query
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with insert query " . mysqli_error($GLOBALS['con']));//insert record into death_timer table
					
					$q = "update Death_timer set last_feed='$date' where pid=" . $tagging_player_info['pid'];
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error updating timer" . mysqli_error($GLOBALS['con']));//update tagging players timer

					/*
					$q = "select pid from Player where p_email = '$share1' and status = 2";
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					if(mysqli_num_rows($result) == 1)
					{
						$result_array = mysqli_fetch_array($result);
						$q = "update Death_timer set last_feed='$date' where pid=" . $result_array['pid'];
						mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					}

					$q = "select pid from Player where p_email = '$share2' and status = 2";
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					if(mysqli_num_rows($result) == 1)
					{
						$result_array = mysqli_fetch_array($result);
						$q = "update Death_timer set last_feed = '$date' where pid = " . $result_array['pid'];
						mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					}

					$q = "select pid from Player where p_email = '$share3' and status = 2";
					$result = mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					if(mysqli_num_rows($result) == 1)
					{
						$result_array = mysqli_fetch_array($result);
						$q = "update Death_timer set last_feed = '$date' where pid = " . $result_array['pid'];
						mysqli_query($GLOBALS['con'], $q) or die ("Error with query " . mysqli_error($GLOBALS['con']));
					}
					*/
					$q = "select * from Game";
					$result_game = mysqli_query($GLOBALS['con'], $q) or die ("Error with query" . mysqli_error($GLOBALS['con']));
					$game_info = mysqli_fetch_array($result_game);

					//send email to notify player that they are now a zombie.
					$to = $tagged_player_info['p_email'];
					$subject = "Welcome to the Horde!" . date("Y");
					$message = "Welcome you are now part of the Zombie Horde.  You will have " . $game_info['death_time_length'] . " hours before you die, you must feed within that time to stay in the game.  Happy Hunting!";
					mail($to, $subject, $message, $emailHeader);

					//return "Successfully tagged player " . $tagged_player_info['f_name'] . " " . $tagged_player_info['l_name'];
					return 1;
				}
				else
				{
					return "<span style='color:red;'>Error tagging player.</span>";
				}
			}
			else
				return "<span style='color:red;'>Invalid player code</span>";
		}
		else
			return "<span style='color:red;'>You are not a zombie</span>";

	}

	/* returns the number of current human players*/
	function get_num_humans()
	{
		$q = "select * from Player where status=3 or status = 5";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Cannot run query" . mysqli_error);
		return mysqli_num_rows($result);
	}

	/* returns the number of current zombie players*/
	function get_num_zombies()
	{
		$q = "select * from Player where status=2";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Cannot run query" . mysqli_error);
		return mysqli_num_rows($result);
	}

	/* returns the number of current dead players*/
	function get_num_dead()
	{
		$q = "select * from Player where status=1";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Cannot run query" . mysqli_error);
		return mysqli_num_rows($result);
	}

	/* Checks whether or not registration is open */
	function get_reg_status()
	{
		$q = "select registration from Game";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Cannot run query" . mysqli_error);
		$result = mysqli_fetch_array($result);
		if($result['registration'] == "OPEN")
			return true;
		else
			return false;
	}

	function check_login($username, $password)
	{
		if($username != "")
		{
			//$password = md5($password);
			//create safe query for mysqli
			$q = sprintf('select * from Player where p_email="%s"', mysqli_real_escape_string($GLOBALS['con'], $username));
			
			//runs query and counts rows.
			$result = mysqli_query($GLOBALS['con'], $q) or die ('Unable to run query:'.mysqli_error($GLOBALS['con']));
			$result_array = mysqli_fetch_assoc($result);
			
			$pass_hash = $result_array['p_password'];
			
			//see if any rows matched and returns error to user if anythign is wrong with login info
			if(password_verify($password, $pass_hash))
			{
				$_SESSION['username'] = $username;
				return ' ';
			}
			else if ($username == '' && $password == '')
				return 'Please enter a Username and password';
			else
				return 'Invalid username or password';
		}
	}

	function create_kill_code()//returns a 6 digit code of random letters and numbers
	{
		$char_array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');//characters allowed in kill code

		$code = "";//code variable

		for($i = 0; $i < 6; $i++)//loop 6 times
		{
			$code .= $char_array[rand(0, count($char_array) - 1)];//choose a random character from the array and concatenate
		}
		return $code;//return the code
	}

	/* creates a 6 digit player id and checks the database to make sure its unique */
	/* returns the id                                                              */
	function create_player_id()
	{
		$counter = 0;
		do
		{
			$random_id = rand(100000,999999);
			$q = "select * from Player where pid=$random_id";
			$result = mysqli_query($GLOBALS['con'], $q) or die ("Error running query" . mysqli_error($GLOBALS['con']));
			$counter++;
		}while(mysqli_num_rows($result) > 0 && $counter < 900001);

		return $random_id;
	}
	
	/* Selects a team for the player beging created.  This is only used in the create_player function so far.  Also keeps the teams balanced */
	function choose_team()
	{
		$q = "select team from Player where team = 1 and status = 3";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Error getting data " . mysqli_error($GLOBALS['con']));
		$team1_count = mysqli_num_rows($result);
		
		$q = "select team from Player where team = 2 and status = 3";
		$result = mysqli_query($GLOBALS['con'], $q) or die ("Error getting data " . mysqli_error($GLOBALS['con']));
		$team2_count = mysqli_num_rows($result);
		
		if($team1_count < $team2_count)
		{
			return 1;
		}
		else if($team2_count < $team1_count)
			return 2;
		else
			return round(rand(1, 2));
	}
	
	function create_player()
	{
		if(!isset($_POST['player_first']) || !isset($_POST['player_last']) || !isset($_POST['player_email']) || !isset($_POST['player_pass']) || !isset($_POST['living_area']) || !isset($_POST['player_accept']))
		 	return "Please enter all the required data.";
		else
		{
			$fname = mysqli_real_escape_string($GLOBALS['con'], $_POST['player_first']);
			$lname = mysqli_real_escape_string($GLOBALS['con'], $_POST['player_last']);
			$email = mysqli_real_escape_string($GLOBALS['con'], $_POST['player_email']);
			$living_area = mysqli_real_escape_string($GLOBALS['con'], $_POST['living_area']);
		}
		
		//make safe entered password
		$pass = mysqli_real_escape_string($GLOBALS['con'], $_POST['player_pass']);
		//Encrypt the password
		$pass = password_hash($pass, PASSWORD_BCRYPT);
		
		
		if($_POST['player_alpha'] == 'yes')
			$alpha = 1;
		else
			$alpha = 0;

		//Check email for validity
		$q = sprintf("select * from Player where p_email='%s'", $email);//check for duplicate email
		$result = mysqli_query($GLOBALS['con'], $q) or die ('Unable to run query:' . mysqli_error($GLOBALS['con']));
		if(mysqli_num_rows($result) > 0)//if row found then the email already exsists.
			return "This email already exists";
		
		//make PID
		$pid = create_player_id();

		//file upload code begin, this isn't working, and it is really annoying right now.
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["player_image"]["name"]);
		$extension = strtolower(end($temp));
		
		if ((($_FILES["player_image"]["type"] == "image/gif")
		|| ($_FILES["player_image"]["type"] == "image/jpeg")
		|| ($_FILES["player_image"]["type"] == "image/jpg")
		|| ($_FILES["player_image"]["type"] == "image/png"))
		&& (in_array($extension, $allowedExts)))
		{
		  if ($_FILES["player_image"]["error"] > 0)
		  {
		  	return "Return Code: " . $_FILES["player_image"]["error"] . "<br>";
		  }
		    if (file_exists("player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension))
		    {
			    	unlink("player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension);
					move_uploaded_file($_FILES["player_image"]["tmp_name"],
						"player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension);
		    }
		    else
		    {
		      move_uploaded_file($_FILES["player_image"]["tmp_name"],
		      "player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension);
		    }
		}
		else
		{
		  return "Invalid file";
		}

		//select a team
		$team = choose_team();

		$pic = "player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension;//save location to put into database
		//file upload end
		$pic = str_replace(" ", "", $pic);//take spaces out of picture location name.
		
		do//create code until you get unique one.
		{
			$kill_code = create_kill_code();
			$q = "select * from Player where kill_code='$kill_code'";//see if the killcode exsists yet.
			$result = mysqli_query($GLOBALS['con'],  $q);
		}while(mysqli_num_rows($result) != 0);
		
		$q = sprintf("insert into Player (f_name, l_name, pid, p_email, p_password, living_area, alpha, picture_location, kill_code, status, paid, active, admin, vaccinated, team) values ('%s', '%s', %s, '%s', '%s', '%s', %s, '%s', '%s', %s, %s, %s, %s, %s, %s)", $fname, $lname, $pid, $email, $pass, $living_area, $alpha, $pic, $kill_code, 3, 0, 0, 0, 0, $team); //mysqli query

		$result = mysqli_query($GLOBALS['con'], $q) or die ('Unable to run query:'.mysqli_error($GLOBALS['con']));
		if($result == 1)//returns "" if no error or returns database error if query didn't work also sends email
		{
			if($team == 1)
			{
				$team = "Alexandrians";
			}
			else
				$team = "The Saviors";
				
			$_SESSION['username'] = $email;

			//email send start
			$to = $email;
			$subject = "APU HVZ - Account Activation " . date("Y");
			$message = "<html><body>Welcome to APU HVZ, to activate your account please click the link below.\n\n
			<a href='https://apuhvz.com?page=activate&code=$kill_code&user=$email'>ACTIVATE</a>" . 
			"<br><br><p>You are part of the team $team</p></body></html>";
			$activeEmailHeader = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset=ISO-8859-1' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
			mail($to, $subject, $message, $activeEmailHeader);//send activation email.
			//email send end
			return "";
		}
		else
			return "database error";
	}

	/* This function gets the entire player row based off the current session logged in. */
	function get_player_info($username)
	{
		$user = mysqli_real_escape_string($GLOBALS['con'], $username);//username in session

		$q = "select * from Player where p_email='$user'";//query all player info
		$result = mysqli_query($GLOBALS['con'], $q) or die ("An error occured with the query " . mysqli_error($GLOBALS['con']));//run query and save result
		$result_array = mysqli_fetch_array($result);//get array from result

		return $result_array;
	}

	/* changes the current users image from teh $_FILES array from a form.  This really isn't secure to allow people to upload files,
	 * but it is needed for the game.
	 */
	function change_user_image($user)
	{
		$result_array = get_player_info($user);//grabs info for current player logged in.

		$fname = $result_array['f_name'];
		$lname = $result_array['l_name'];
		$pid = $result_array['pid'];

		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = strtolower($_FILES['player_image']['name']);
		$temp = explode(".", $temp);
		$extension = end($temp);
		if($temp[1] != "")
		{
			if ((($_FILES["player_image"]["type"] == "image/gif")
			|| ($_FILES["player_image"]["type"] == "image/jpeg")
			|| ($_FILES["player_image"]["type"] == "image/jpg")
			|| ($_FILES["player_image"]["type"] == "image/png"))
			&& (in_array($extension, $allowedExts)))
			{
			  if ($_FILES["player_image"]["error"] > 0)
			  {
				  return "Return Code: " . $_FILES["player_image"]["error"] . "<br>";
			  }
	
			  unlink($result_array['picture_location']);
			  move_uploaded_file($_FILES["player_image"]["tmp_name"],
			  "player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension);
			  $new_location = "player_pics/" . strtolower(str_replace(" ", "", $fname)) . strtolower(str_replace(" ", "", $lname)) . $pid . "." . $extension;
			  str_replace(" ", "", $new_location);
			  $new_location = mysqli_real_escape_string($GLOBALS['con'], strtolower($new_location));
			  $q = "update Player set picture_location='$new_location' where p_email='".$_SESSION['username']."'";
			  $result = mysqli_query($GLOBALS['con'], $q) or die ("there was an error".mysqli_error($GLOBALS['con']));
			  if($result)
			  	return "";
			  else
			  	return "Error updating profile picture";
			}
			else
			{
			  return "Invalid file";
			}
		}
		else
			return "Please choose a file to upload.";
	}
?>
