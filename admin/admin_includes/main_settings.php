<?php
	session_start();
	ob_start();
	
	if(!check_admin_status($_SESSION['username']))
		header("Location: /index.php?page=home");
?>
<div style="padding: 10px;">
	<script>
		var xmlhttp;
		if(window.XMLHttpRequest)
		{//code for IE7+ and the others
			xmlhttp = new XMLHttpRequest();
		}
		else
		{//IE5 and IE6 code
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		function change_reg_status()
		{
			//updates page with result of game
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("registration_status").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET", "AJAX_functions/change_reg_status.php", true);
			xmlhttp.send();
		}
		
		function change_starve_time()
		{
			//updates page with result of game
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("starve_time").innerHTML=xmlhttp.responseText;
				}
			}
			var time_value = document.getElementById("new_starve").value;
			xmlhttp.open("GET", "AJAX_functions/change_starve_time.php?time="+time_value, true);
			xmlhttp.send();
		}
		
		function reveal(email)
		{
			//updates page with result of game
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					//document.location.reload(true);
					var element = document.getElementById(email);
					element.innerHTML = xmlhttp.responseText;
					element.disabled = "disabled";
				}
			}
			xmlhttp.open("GET", "AJAX_functions/reveal_alpha.php?email="+email, true);
			xmlhttp.send();
		}
		
		function set_shares()
		{
			//updates page with result of game
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("shares").innerHTML=xmlhttp.responseText;
				}
			}
			var share_value = document.getElementById("new_shares").value;
			xmlhttp.open("GET", "AJAX_functions/AJAX_change_shares.php?shares="+share_value, true);
			xmlhttp.send();
		}
		
		function end_game()
		{
			//updates page with result of game
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("game_end_date").innerHTML=xmlhttp.responseText;
				}
			}
			if(confirm("Are you sure you want to end the game?"))
			{
				xmlhttp.open("LOAD", "AJAX_functions/AJAX_end_game.php", true);
				xmlhttp.send();
			}
		}
	</script>
		Current game created on: 
		<span id="game_date" style="font-weight:bold;">
		<?php 
			$info = get_game_info();
			echo($info['start_date']);
		?>
		</span><br />
		Game Ended on: <span id="game_end_date" style="font-weight: bold;"></span>
		<br /><br />
		Create a New Game here:<br />
		<form action="<?php htmlspecialchars($_SERVER[PHP_SELF]) ?>" method="POST">
		<?php
			if(isset($_POST['create']))
			{
				if(!strlen($_POST['default_email']) < 5 && !$_POST['default_pass'] < 3)
				{
					$game_return = create_game($_POST['default_email'], $_POST['default_pass']);
					if($game_return == true)
						header("Location: /index.php");
					else
						echo("<span style='color:red;'>" . $game_return . "<br /></span>");
				}
				else
					echo "<span style='color:red;'>Please enter a default email or password.<br /></span>";
			}
		?>
			Enter an email for default account: <input type="text" placeholder="Default email" name="default_email" /><br />
			Enter a Password: <input type="text" placeholder="Default account password" name="default_pass" /><br />
			<input type="submit" value="Create Game!" name="create" /><br />
		</form><br />
		<button onclick="end_game()">End Game</button>
		<br /><br />
		
		<!-- Open and close the sign-up page here.  Calls change_reg_status() javascript ajax function. -->
		Registration Status: <span id="registration_status"><?php echo(get_reg_status()); ?></span><br />
		<button onclick="change_reg_status()">OPEN/CLOSE</button><br /><br />
		
		<!-- This is where you set the starve timer.  It calls the change_starve_time() javascript function in this file -->
		Game Starve Time: <span id="starve_time"><?php echo(get_starve_time()); ?></span><br />
		<input type="text" id="new_starve" size="3" placeholder="<?php echo(get_starve_time()); ?>" />
		<button onclick="change_starve_time()">Set Starve</button><br />
		<span style="color: red;">Note: If you wish to shorten the starve timer, feed all of the Zombies and then do this right after so that you don't kill anyone.</span><br /><br />
		
		Shares Allowed: <span id="shares"><?php echo(get_shares()); ?></span><br />
		<input type="text" id="new_shares" size="3" placeholder="<?php echo(get_shares()); ?>" />
		<button onclick="set_shares()">Set Shares</button><br /><br />
		
		<?php
			//select an alpha based on the form email data.
			if($_POST['alpha_select'] == "submit")
			{
				select_alpha($_POST['alpha_email']);
			}
			//select a random alpha if the random button is hit.
			else if($_POST['alpha_select'] == "Random")
			{
				select_alpha(1);
			}
		?>
		
		<form method="post" action="admin.php">
			Select Alpha by email: <input type="text" name="alpha_email" />
			<input type="submit" name="alpha_select" value="submit" />
			<input type="submit" name="alpha_select" value="Random" />
		</form>
		<br /><br />
		
		<table>
			<tr><h3>Alphas</h3></tr>
			<?php
				$array = get_alphas();
				//if there are alphas populate the table
				if(count($array) > 0)
				{
					//creates table ehre.
					for($i = 0; $i < count($array); $i++)
					{
						echo "<tr><td style='border-right: 1px solid black;padding: 5px;'>" . $array[$i]['p_email'] . "</td><td>"
						 . $array[$i]['f_name'] . " " . $array[$i]['l_name'] . "</td>" . 
						 "<td><button onclick=reveal('" . $array[$i]['p_email'] . "') id='" . $array[$i]['p_email'] . "'>Reveal Alpha</button></td></tr>";
					}
				}
				else//let them know there are no alphas
					echo "The virus has not infected anyone yet.";
			?>
		</table>
		
</div>
<?php
	ob_flush();
?>