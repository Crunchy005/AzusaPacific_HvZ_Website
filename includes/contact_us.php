<?php
	header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
?>
<div style="padding: 10px;">
	<form method="post" action="/index.php?page=contact_us">
		<table>
			<tr>
				<td>
					E-Mail: <input type="email" name="email" placeholder="E-Mail" /><Br><br>
				</td>
			</tr>
			<tr>
				<td>
					What is your Question?<br>
				</td>
			</tr>
			<tr>
				<td>
				<textarea name="question" placeholder="Write Question here." cols="40" rows="10"></textarea>
				</td>
			</tr>
		</table>
		<input type="submit" name="submit" value="Submit" />
	</form>
</div>
<?php
	$email = $_POST['email'];
	if(isset($_POST['submit']))
	{
		if ( !preg_match( "/[\r\n]/", $email ) && preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email) )
			mail("apu.hvz@gmail.com", "Contact us submission", $_POST['question'], "From: " . $email . "\r\nReplay-to: " . $email . "\r\n\r\n");
	}
?>