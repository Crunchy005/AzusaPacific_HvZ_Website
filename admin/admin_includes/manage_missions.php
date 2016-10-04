<?php
	//check for admin login security.
	if(!check_admin_status($_SESSION['username']))
		header('location: /index.php');
	//Create mission submit
	if(isset($_POST['submit']) && $_POST['submit'] == "create_mission")
	{
		$mission_email = $_POST['email_text'];
		$mission_email = mysqli_real_escape_string($con, $mission_email);
		$mission_name = $_POST['mission_name'];
		$mission_name = mysqli_real_escape_string($con, $mission_name);
		$q = "insert into Mission (mission_name, mission_email) values ('$mission_name', '$mission_email')";
		$result = mysqli_query($con, $q) or die ("Error inserting mission " . mysqli_error($con));
	}
	//update mission submit
	if(isset($_POST['submit']) && $_POST['submit'] == "update_mission")
	{
		$mission_email = $_POST['email_text'];
		$mission_email = mysqli_real_escape_string($con, $mission_email);
		$mission_name = $_POST['mission_name'];
		$mission_name = mysqli_real_escape_string($con, $mission_name);
		$mission_id = $_POST['mission_id'];
		$q = "update Mission set mission_name = '$mission_name', mission_email = '$mission_email' where mission_id=$mission_id";
		$result = mysqli_query($con, $q) or die ("Error updating mission " . mysqli_error($con));
	}
	//delete mission submit
	if(isset($_POST['submit']) && $_POST['submit'] == "delete_mission")
	{
		$mission_id = $_POST['mission_id'];
		$q = "delete from Mission where mission_id = $mission_id";
		$result = mysqli_query($con, $q) or die ("Error deleting mission " . mysqli_error($con));
		$q = "delete from Mission_att where mission_id = $mission_id";
		$result = mysqli_query($con, $q) or die("error deleting attendance records" . mysqli_error($con));
	}
	//send mission email submit
	if(isset($_POST['submit']) && $_POST['submit'] == "send_email")
	{
		$mission_email = $_POST['email_text'];
		$q = "select p_email from Player where status = 3";
		$result = mysqli_query($con, $q) or die ("Error getting player list for email " . mysqli_error($con));
		while($result_array = mysqli_fetch_assoc($result))
		{
			$email_to .= $result_array['p_email'] . ", ";
		}
		
		$subject = $_POST['mission_name'];
		$emailHeader = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset=ISO-8859-1' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
		
		mail($email_to, $subject, $mission_email, $emailHeaders);
		
	}
?>
<div style="padding: 10px;">
	Select Mission: <br>
	<form action="admin.php?page=manage_missions" method="post">
		<select id='mission_list' name="mission_id">
			<option value="0">Select a Mission</option>
			<?php
				$q = "select * from Mission order by mission_id";
				$result = mysqli_query($con, $q) or die ("Error getting missions " . mysqli_error($con));
				while($result_array = mysqli_fetch_assoc($result))
				{
					echo "<option value='" . $result_array['mission_id'] . "'>" . $result_array['mission_name'] . "</option>";
				}
			?>
		</select>
		<br><br>
		Mission Name: <input type="text" name="mission_name" /><br><br>
		Mission Email: <br><textarea name="email_text" class='email_text'></textarea><br>
		<input type="submit" value="update_mission" name="submit" style="background: black; color: white; margin: 10px;" />
		<input type="submit" value="send_email" name="submit" style="background: black; color: white; margin: 10px;" /><br>
		<input type="submit" value="create_mission" name="submit" style="background: black; color: white; margin: 10px;" /><br>
		<input type="submit" value="delete_mission" name="submit" style="background: black; color: white; margin: 10px;" /><br>
	</form>
</div>
<script>
	$('document').ready(function(){
		$("#mission_list").click(function()
		{
			$.ajax({
				url: "AJAX_functions/get_mission.php",
				type: "POST",
				data: { id: $('#mission_list option:selected').val() }
			})
			.done(function( html )
			{
				$('.email_text').val(html);
				$('input[name=mission_name]').val($("#mission_list option:selected").text());
			});
		});
	});
</script>