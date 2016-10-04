<div style="padding: 15px;">
<?php
	//process player info if form is sent
	if(isset($_POST['submit']))
	{
		$email = trim($_POST['email']);
		$email = mysqli_real_escape_string($con, $email);
		$status = $_POST['status'];
		//get info from database
		$q = "select pid, p_password, status from Player where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("can't get player info " . mysqli_error($con));
		$result_player = mysqli_fetch_assoc($result);
		
		$pid = $result_player['pid'];
		$living_area = $_POST['living_area'];
		$currentTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$currentDate = date("D F j h:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
		
		if($result_player['status'] != $status)//if status changes update status and make necessary changes
		{
			$q = "update Player set p_email='$email', status=$status, living_area = '$living_area' where pid=$pid";
			
			$result = mysqli_query($con, $q) or die ("Error updating info " . mysqli_error($con));
			if($status == 2)//if changing to zombie add death timer entry
			{
				$q = "insert into Death_timer (pid, last_feed) values ($pid, $currentTime)";
				$result = mysqli_query($con, $q) or die ("Unable to add death time " . mysqli_error($con));
				
				//check if tag entry exists
				$q = "select * from Tagged where pid = $pid";
				$result = mysqli_query($con, $q);
				//if no results returned add tagged entry
				if(mysqli_num_rows($result) == 0)
				{
					$q = "insert into Tagged (pid, taggedby_pid, tagged_date) values ($pid, 1, '$currentDate')";
					$result = mysqli_query($con, $q) or die ("Unable to add Tag entry " . mysqli_error($con));
				}
				
				if($_POST['pass'] != "")
				{
					$pass_enc = password_hash($_POST['pass'], PASSWORD_BCRYPT);
					$q = "update Player set p_password = '$pass_enc' where pid = $pid";
					$result = mysqli_query($con, $q) or die ("could not update password " . mysqli_error($con));
				}

			}
			else//if changing to anythign but zombie delete death timer entry and tagged entry
			{
				$q = "delete from Death_timer where pid = $pid";
				$result = mysqli_query($con, $q) or die ("unable to delete from death timer " . mysqli_error($con));
				
				$q = "delete from Tagged where pid = $pid";
				$result = mysqli_query($con, $q) or die ("unable to delete from Tagged " . mysqli_error($con));
			}
		}
		else
		{
			$q = "update Player set p_email='$email', living_area = '$living_area' where pid=$pid";
			$result = mysqli_query($con, $q) or die ("Error updating info " . mysqli_error($con));
			if($_POST['pass'] != "")
			{
				$pass_enc = password_hash($_POST['pass'], PASSWORD_BCRYPT);
				$q = "update Player set p_password = '$pass_enc' where pid = $pid";
				$result = mysqli_query($con, $q) or die ("could not update password " . mysqli_error($con));
			}
		}
		
		
		
		if($result)
			echo("Updated $email <br>");
		else
			echo "Error updating player info";
	}
	//reset timer form sent
	if(isset($_POST['reset_time']))
	{
		$q = "select pid, status from Player where p_email = '" . $_POST['email'] . "'";
		$result = mysqli_query($con, $q) or die ("error getting pid " . mysqli_error($con));
		$result_array = mysqli_fetch_assoc($result);
		if($result_array['status'] == 2)
		{
			$newTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
			$q = "update Death_timer set last_feed = $newTime where pid = ' " . $result_array['pid'] . " '";
			$result = mysqli_query($con, $q) or die ("Error updating time " . mysqli_error($con));
		}
 	}
 	//activate player
 	if(isset($_POST['activate_player']))
 	{
	 	$q = "update Player set active = 1 where p_email = '" . $_POST['email'] . "'";
	 	$result = mysqli_query($con, $q);
	 	if($result)
	 	{
		 	echo "Player Activated";
	 	}
 	}
 	//get email from get array and display info, either link clicked or search form sent.
	if(isset($_GET['email']))
	{
		$email = mysqli_real_escape_string($con, $_GET['email']);
		$email = trim($email);
		$q = "select * from Player where p_email = '$email'";
		$result = mysqli_query($con, $q) or die ("Error getting player info " . mysqli_error($con));
		if(mysqli_num_rows($result) > 0)//if player exists create profile page
		{
			$result_array = mysqli_fetch_assoc($result);
			echo("<p>Name: " . $result_array['f_name'] . " " . $result_array['l_name'] . "<br>");
			echo("<img alt='profile_img' width='300px' src='../" . $result_array['picture_location'] . "' /><br>");
?>
	<p>Player kill code: <?php echo $result_array['kill_code']; ?></p>
	<form method="post" action="admin.php?page=profile_admin">
		Set Status: 
		<select name="status">
			<option value='3'<?php if($result_array['status']==3) echo(" selected "); ?>>Human</option>
			<option value='2'<?php if($result_array['status']==2) echo(" selected "); ?>>Zombie</option>
			<option value='1'<?php if($result_array['status']==1) echo(" selected "); ?>>dead</option>
			<option value='4'<?php if($result_array['status']==4) echo(" selected "); ?>>Staff</option>
			<option value='5'<?php if($result_array['status']==5) echo(" selected "); ?>>Alpha</option>
		</select><br><br>
		Select Living area: 
		<select name="living_area">
					<option value="">Select Living Area</option><br>
					<option value="University Village" <?php if($result_array['living_area']=='University Village') echo(" selected "); ?>> University 
					Village</option><br />
					<option value="University Park" <?php if($result_array['living_area']=='University Park') echo(" selected "); ?>> University Park</
					option><br />
					<option value="Engstrom" <?php if($result_array['living_area']=='Engstrom') echo(" selected "); ?>> Engstrom</option><br />
					<option value="Adams" <?php if($result_array['living_area']=='Adams') echo(" selected "); ?>> Adams</option><br />
					<option value="Smith" <?php if($result_array['living_area']=='Smith') echo(" selected "); ?>> Smith</option><br />
					<option value="Bowles" <?php if($result_array['living_area']=='Bowles') echo(" selected "); ?>> Bowles </option><br />
					<option value="Alosta" <?php if($result_array['living_area']=='Alosta') echo(" selected "); ?>> Alosta </option><br />
				</select><br><br>
		Email: <input type="email" name="email" value="<?php echo $result_array['p_email']; ?>" autocomplete="off" /><br>
		Change Password: <input type="password" name="pass" placeholder="Change password" autocomplete="off" /><br>
		<input type="submit" name="submit" value="submit" />
	</form>
	<?php if($result_array['active'] == 0)
		{ ?>
	<form method="post" action="admin.php?page=profile_admin">
		<input type="submit" name="activate_player" value="Activate Player" />
		<input type="hidden" name="email" value="<?php echo $_GET['email'] ?>" />
	</form>
	<br><br>
	<?php
		}
			if($result_array['status'] == 2)
			{	
	?>
	<form method="post" action="admin.php?page=profile_admin">
		<input type="submit" name="reset_time" value="Reset Timer" />
		<input type="hidden" name="email" value="<?php echo $_GET['email'] ?>" />
	</form>
	<?php
			}
	?>
<?php
		}
		else
		{
			echo("Player doesn't exist");
		}
	}
	else
	{
?>
	<span id="errors"></span>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Player E-mail: <input type="text" name="email" />
		<input type="submit" value="submit" />
		<input type="hidden" name="page" value="profile_admin" />
	</form><br>
	<button class="reset_timers">Reset All Timers</button><br><br>
<?php
		$q = "select * from Player order by f_name";
		$result = mysqli_query($con, $q) or die ("Error getting player info " . mysqli_error($con));
		while($result_array = mysqli_fetch_assoc($result))
		{
			echo("<a href='admin.php?page=profile_admin&email=" . $result_array['p_email'] . "'>" . $result_array['f_name'] . " " .
				$result_array['l_name'] . "</a><br>");
		}
	}
?>
<br><br>
<?php
	if(isset($_GET['email']))
		echo "<a href='admin.php?page=profile_admin'>Back to Player Select</a>";
?>
</div>

<!-- Call AJAX function here to reset all timers -->
<script>
	$(document).ready(function(){
		$(".reset_timers").click(function(){
			alert("clicked");
			$.get("AJAX_functions/reset_timers.php",function(data, status){
				alert("Data: " + data + " Status: " + status);
  			});
		});
	});
</script>
