<?php
	session_start();
	include("../admin_functions.php");
	include("../db_con.php");
	
	if(check_admin_status($_SESSION['username']))
	{
		end_game();
	}