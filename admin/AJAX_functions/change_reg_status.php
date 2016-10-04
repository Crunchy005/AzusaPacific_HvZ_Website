<?php
	session_start();
	include("../admin_functions.php");

	if(check_admin_status($_SESSION['username']))
	{
		$game_info = get_game_info();
		
		if($game_info['registration'] == "OPEN")
		{
			if(change_reg_status("CLOSE"))
				echo("CLOSED");
			else
				echo("error");
		}
		else
		{
			if(change_reg_status("OPEN"))
				echo("OPEN");
			else
				echo("error");
		}
	}

?>