<?php
	session_start();
	include('../admin_functions.php');
	include("../db_con.php");
	if(check_admin_status($_SESSION['username']))
	{
		if(isset($_GET['mission_id']) && !isset($_GET['pid']))
		{	
			$mission = $_GET['mission_id'];
			$q = "select f_name, l_name, pid from Player where status = 3";//get all human players
			$result = mysqli_query($con, $q);//run query and save it.
				
			//loop and output players to page
			while($result_array = mysqli_fetch_assoc($result))
			{
				//see if they already have a attendance for this mission
				$q = "select pid, mission_id from Mission_att where pid = " . $result_array['pid'] . " and mission_id = $mission";
				$result2 = mysqli_query($con, $q);
				
				//if no attendance found echo checkbox and name for input
				if(mysqli_num_rows($result2) == 0 && $mission != 0)
				{
					echo "<li><input type='radio' name='player[]' id='player_attend' value='" . $result_array['pid'] . "' />" . $result_array['f_name'] . " " . $result_array['l_name'] . 
					"</li>";//output the players with checkbox inputs.
				}
			}
		}
		else if(isset($_GET['pid']) && isset($_GET['mission_id']))
		{
			$pid = $_GET['pid'];
			$m_id = $_GET['mission_id'];
			//echo("insert into mission_att (pid, mission_id) values ($pid, $m_id)");
			$q = "insert into Mission_att (pid, mission_id) values ('$pid', $m_id)";
			$result = mysqli_query($con, $q) or die ("error giving attendance " . mysqli_error($con));
			if($result)
				echo true;
		}
	}
?>