<!--
	This is the login form that you see on the main page.
-->

<form name="login" method="post" action="index.php">
	<span style="font-family: 'Bebas'">User Name: </span>
	<br/>
	<input type="email" name="uname" placeholder="Email" value="<?php echo($_POST['uname']); ?>" />
	<br/>
	<br/>
	<span style="font-family: 'Bebas'"> Password: </span>
	<br/>
	<input type="password" name="pass" placeholder="Password" style="font-family" />
	<br/>
	<br/>
	<table align="center" style="padding: 5px;">
		<tr>
			<td style="padding: 5px;">
				<input class="button" style="width: auto; height: auto; border: none;" type="Submit" value="Login" alt="submit form"/>
			</td>
			<td style="border-left: solid #87171D 2px; padding: 5px;">
				<input class="button" style="width: auto; height: auto; border: none;" type="button" value="Sign Up" onclick="window.location='sign_up.php';">
			</td>
		</tr>
	</table>
</form>