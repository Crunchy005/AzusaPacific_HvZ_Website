<?php
	include("header.php");
	$mission_id = $_POST['id'];
	
	$q = "Select mission_id, m.mission_name, pid, f_name, l_name from Mission m left join Mission_att ma using(mission_id) left join Player using(pid) where mission_id = $mission_id";
	$result = mysqli_query($con, $q) or die ("error getting mission attendance " . mysqli_error($con));
	$result_array = mysqli_fetch_assoc($result);
	
	echo("<table class='sortable' style='width: 70%;'>\n");
	echo("<tr><th colspan='2'>" . $result_array['mission_name'] . "</th></tr>\n");
	echo("<tr><td>" . $result_array['f_name']) . " " . $result_array['l_name'] . "</td></tr>";

	while($result_array = mysqli_fetch_assoc($result))
	{
		echo("<tr><td>" . $result_array['f_name']) . " " . $result_array['l_name'] . "</td></tr>";
	}
	echo "</table>";	
?>