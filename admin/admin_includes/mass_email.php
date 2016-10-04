<?php
	//blah blah security stuff
	session_start();
	ob_start();
	if(!check_admin_status($_SESSION['username']))
		header('location: /index.php');
?>
<div style="padding: 10px;">
	<form method="post" action="?page=mass_email">
		<table>
			<tr>
				<td style="vertical-align: top;">Subject:</td><td><input type="text"  name="subject" /><br><br></td>
			</tr>
			<tr>
				<td style="vertical-align: top;">Message:</td><td><textarea name="message" cols="50" rows="4" placeholder="Message goes here."></textarea></td>
			</tr>
			<tr>
				<td>Send to: </td>
				<td>
					<select name="email_type">
						<option value="5">Humans/Zombies</option>
						<option value="2">Zombies</option>
						<option value="3">Humans</option>
						<option value="6">Everyone</option>
						<option value="7">Unpaid Players</option>
						<option value="8">Not active</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" />
	</form>
</div>
<?php
	if(isset($_POST['submit']))
	{
		$q = "select p_email from Player where status = 4";//get all admin emails
		$result = mysqli_query($con, $q) or die ("Error getting emails " . mysqli_error($con));//run query generate error
		$result_array = mysqli_fetch_array($result);//fetch the info
		//set up header
		$ccHeader = "From: APU HVZ " . date("Y") . "<noreply@apuhvz.com>" . "\r\n" . "Reply-To: apu.hvz@gmail.com" . "\r\n";
		//bcc all admins
		$ccHeader .= "BCc: " . $result_array['p_email'];
		
		//populate bcc with admin emails
		while($result_array = mysqli_fetch_array($result))
		{
			$ccHeader .= ", " . $result_array['p_email'];
		}
		
		$type = $_POST['email_type'];//pull from post array
		if($type == 5)//set proper query depending on email audience selection
			$q = "select p_email from Player where status = 3 or status = 2 or status = 5";
		else if($type == 6)
			$q = "select p_email from Player";
		else if ($type == 2)
			$q = "select p_email from Player where status = 2 or status = 5";
		else if($type == 3)
			$q = "select p_email from Player where status = 3";
		else if($type == 7)
			$q = "select p_email from Player where paid = 0";
		else if($type == 8)
			$q = "select p_email from Player where active = 0";
		
		$result = mysqli_query($con, $q) or die ("Error getting data: " . mysqli_error($con));
		
		//populate bcc header with player emails for audience
		while($result_array = mysqli_fetch_array($result))
		{
			$ccHeader .= ", " . $result_array['p_email'];
		}
		//finish bcc header
		$ccHeader .= "\r\n";
		//add in functionality for HTML emails
		$ccHeader .= 'X-Mailer: PHP/' . phpversion() . "\r\n" .
        'Content-Type: text/html; charset: utf8\r\n'."\r\n".
        'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
        'Organization: Azusa Pacific university\r\n' . 
        "X-Priority: 3\r\n" . 
        'MIME-Version: 1.0'."\r\n\r\n";
        //send the email
		mail("apu.hvz@gmail.com", $_POST['subject'], $_POST['message'], $ccHeader);
		//let user know they were sent
		echo('Messages sent.');
	}
	ob_flush();
?>