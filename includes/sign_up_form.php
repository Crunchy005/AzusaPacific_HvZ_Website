<!--
<div class="center">
	<h1> Sign up for APU HVZ here!</h1>
	<form method="post" action="?signup_submit" class="form-center">
		<div style="text-align: center;">
			<div style="width: 250px; text-align: right; float: left;">
				<p>
				First Name:<br/>
				Last Name:<br/>
				Email:<br/>
				Password:<br/>
				Confirm Password:<br/>
				</p>
			</div>
			<div style="text-align: left; margin-left: 25px;">
				<p>
				<input type="text" name="player_first" /><br/> 
				<input type="text" name="player_last" /><br/>
				<input type="email" name="player_email"/><br/>
				<input type="password" name="player_pass" /><br/>
				<input type="password" name="player_pass_conf" />
				</p>
			</div>
		</div>
		
		<br/>
		<br/>
		<h3> Select your living area: </h3>
		<input type="radio" name="living_area" value="University Village" /> University Village<br />
		<input type="radio" name="living_area" value="University Park" /> University Park<br />
		<input type="radio" name="living_area" value="Engstrom Hall" /> Engstrom Hall<br />
		<input type="radio" name="living_area" value="Adams Hall" /> Adams Hall<br />
		<input type="radio" name="living_area" value="Smith Hall" /> Smith Hall<br />
		<input type="radio" name="living_area" value="Bowles" /> Bowles<br />
		<h3>Woud you like to be in the drawing for Alpha Zombie?</h3>
		<input type="radio" name="player_alpha" value="yes" /> Yes <br/>
		<input type="radio" name="player_alpha" value="no" /> No <br/>
		<br/>
		<p>By clicking the box you agree to abide by all rules on the HvZ website and all rules set by the University throughout the duration of the game.
			<br/> <input type="checkbox"/> I agree
		</p>
		<br/><br/>
		<input type="submit" value="Sign Up!" name="Submit" />
	</form>
</div>
<script>
</script>
<?php
	/*if(isset($_POST['signup_submit']))
		create_player();*/
?>
-->
<table>
	<tr>
		<td>
			<h1 style="color:green; font-family: 'Bebas'; text-align: center;">Sign Up for APU HVZ here!</h1>
			<?php
				if(isset($_POST['submit']))
				{
					$result = create_player();
					if($result == '')
						header("location:/index.php");
					else
						echo("<p style='color:red'>".$result."</p>");
				}
			?>
			<form method="post" name="signup" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-center">
				First Name: &nbsp; &nbsp; &nbsp;<input type="text" name="player_first" placeholder="First Name" value="<?php echo($_POST['player_first']); ?>" />
				<br /><br />
				Last Name: &nbsp; &nbsp; &nbsp;<input type="text" name="player_last" placeholder="Last Name" value="<?php echo($_POST['player_last']); ?>" />
				<br /><br />
				Email: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="email" name="player_email" placeholder="E-Mail" value="<?php echo($_POST['player_email']); ?>" />
				<br /><br />
				
				Password: &nbsp; &nbsp; &nbsp; &nbsp;<input type="password" name="player_pass" /><span id="pass_valid"></span>
				
				<br /><br />
				
				Confirm Password: &nbsp; &nbsp; &nbsp;<input type="password" name="player_pass_conf" /><span id="pass_match"></span>
				
				<h3>Select your living area: </h3>
				
				<!-- Maybe we can make this a dropdown, so that it's easier for mobile? -->
				<select name="living_area">
					<option value="Adams"> Adams</option><br />
					<option value="Alosta"> Alosta </option><br />
					<option value="Bowles"> Bowles </option><br />
					<option value="Engstrom"> Engstrom</option><br />
					<option value="Mods">Mods</option><br />
					<option value="Smith"> Smith</option><br />
					<option value="Trinity">Trinity</option><br />
					<option value="University Park"> University Park</option><br />
					<option value="University Village"> University Village</option><br />
				</select>
				<h4>Would you like to be in the drawing for Alpha Zombie?</h4>
				<input type="radio" name="player_alpha" value="yes" /> Yes &nbsp;
				<input type="radio" name="player_alpha" value="no" /> No <br /><br />
				<?php
					if(isset($_POST['submit']))
					{
						if(!isset($_FILE['player_image']))
							echo("<span style='color:red;'>A photo is required.</span>");
					}
				?>
				<p>Upload an image: This should be an image of just your face.</p>
				<input type="file" name="player_image" /><br /><br />
				
				By clicking this box you agree to abide by all the rules on the HVZ website and all rules set by the University throughout the duration of the game.<br/>
				<input type="checkbox" name="player_accept" />  I agree.
				<br />
				<?php
					if(isset($_POST['submit']))
					{
						if(!isset($_POST['player_accept']))
							echo("<span style='color:red;'>You need to accept agreement.</span>");
					}
				?>
				<br />
				<input type="submit" value="Sign Up!" name="submit" />
			</form>
		</td>
	</tr>
</table>
