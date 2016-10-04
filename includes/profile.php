<?php
	if(isset($_SESSION['username']))
	{	
		$user = mysqli_real_escape_string($con, $_SESSION['username']);
		
		//query database for player information
		$result_array = get_player_info($user);
		
		$player_name = $result_array['f_name'] . " " . $result_array['l_name'];
		
		$player_image = $result_array['picture_location'];//save image location from the array
		//check the player status and save the appropriate string
		if($result_array['status'] == 3)//output status in friendly format
			$player_status = "Human";
		else if($result_array['status'] == 2)
			$player_status = "Zombie";
		else if($result_array['status'] == 1)
			$player_status = "Dead";
		else if($result_array['status'] == 4)
			$player_status = 'Moderator';
		else if($result_array['status'] == 5)
			$player_status = 'Alpha Zombie';
			
		//store kill code
		$player_code = $result_array['kill_code'];
		//store living area
		$player_living_area = $result_array['living_area'];
		//store team info
		if($result_array['team'] == 1)
			$team = "Alexandrians";
		else
			$team = "The Saviors";
		
		if($result_array['alpha'] == 1)
			$player_alpha = "Yes";
		else
			$player_alpha = "No";
		if($result_array['active'] == 0)
		{
			echo "Your account is not active!  Please activate your account.  An activation Email Was sent when you signed up.  Don't forget to check your spam folder";
			$email = $_SESSION['username'];
	
			//email send start
			$to = $email;
			$subject = "APU HVZ - Account Activation";
			$message = "Welcome to APU HVZ, to activate your account please click the link below.\n\n
			http://apuhvz.com/index.php?page=activate&code=$player_code&user=$email";
			mail($to, $subject, $message, $emailHeader);//send activation email.
			//email send end
			return "";
		}
		else
		{
?>
<img alt="profile pic" name="player_image" style="float:right;margin:10px;" width="300px" src="<?php echo($player_image); ?>" />

<div id="profile_info">
	<b>Profile for <?php echo($player_name); ?></b><br><br>
	Player Status: <?php echo($player_status); ?><br>
	Living Area: <?php echo($player_living_area); ?><br>
	In drawing for alpha? <?php echo($player_alpha); ?><br><br>
	<b>Player Code: <?php echo($player_code); ?></b><br>
	<b>Team: <?php echo($team); ?><Br><Br></b>
	Missions Attended: 
	<?php
		$q = "select * from Mission_att where pid = " . $result_array['pid'];
		$result_missions = mysqli_query($con, $q) or die ("Error with query " . mysqli_error($con));
		echo mysqli_num_rows($result_missions);
	?>
	<br>
	Total Vaccinations: 
	<?php
		$q = "select * from Vaccination where pid=" . $result_array['pid'];
		$result = mysqli_query($con, $q);
		echo(mysqli_num_rows($result) . "<br><br>");
		
		if($result_array['status'] <= 2)
		{
			$pid = $result_array['pid'];//get player ID
			$q = "select t.pid, t.tagged_date, p.f_name, p.l_name from Tagged t JOIN Player p on p.pid = t.taggedby_pid where t.pid = $pid";//create query
			$result = mysqli_query($con, $q) or die ();//run query
			$tagged_array = mysqli_fetch_array($result);//get array info
			echo("Killed By: " . $tagged_array['f_name'] . " " . $tagged_array['l_name'] . " on " . $tagged_array['tagged_date'] . "<br>");//echo results
			$q = "select * from Tagged where taggedby_pid='" . $result_array['pid'] . "'";
			$result = mysqli_query($con, $q);
			echo("Number of Kills: " . mysqli_num_rows($result) . "<br><br>");
		}
	?>
	
	<form method="post" name="change_pic" enctype="multipart/form-data" action="/index.php?page=profile">
		<input type="file" name="player_image" />
		<input type="submit" name="picture" value="Change Picture" />
	</form><br><br>
	Change Password:
	<form method="post" action="/index.php?page=profile"><br>
		Old Password: <input type="password" name="old_pass" /><br>
		New Password: <input type="password" name="new_pass" /><br>
		Confirm New Password: <input type="password" name="confirm_new_pass" /><br>
		<input type="submit" name="password_change" value="Change Password" />
	</form>
	
	<?php
			if(isset($_POST['password_change']))
			{
				if(update_user_pass($_SESSION['username'], $_POST['old_pass'], $_POST['new_pass'], $_POST['confirm_new_pass']))
				{
					echo("Password updated");
				}
			}
			if(isset($_POST['picture']))
			{
				if(change_user_image($_SESSION['username']) == "")
					header("location: /index.php?page=profile");
				else
					echo("Invalid File");
			}
		}
	}else
	{
		echo "Your are not logged in.";
	}
	?>
</div>
