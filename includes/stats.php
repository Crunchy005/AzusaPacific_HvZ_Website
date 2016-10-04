<script src="sorttable.js"></script>
<?php
	update_game_status();
?>
<div id="stats">
		<h1>Humans<br><hr />
			<?php 
				echo(get_num_humans());
			 ?>
		</h1>
		<h1>Zombies<br><hr />
			<?php 
				echo(get_num_zombies());
			 ?>
		</h1>
		<h1>Dead<br><hr />
			<?php 
				echo(get_num_dead());
			 ?>
		</h1>
</div>

<h1 class="table_title">HUMANS</h1>
<table class="sortable">
	<tr>
			<th>Player Name</th>
			<th>Living Area</th>
			<th>Team</th>
			<?php 
				if(isset($_POST['pics']))
					echo '<th>Picture</th>';
			?>
	</tr>
	<?php
		$q = "select f_name, l_name, living_area, picture_location, team, tn.team_name from Player p join Team_Name tn on p.team = tn.team_id where status=3 or status = 5";
		$result = mysqli_query($con, $q) or die ("error running query " . mysqli_error($con));
		while($row = mysqli_fetch_array($result))
		{
			echo("<tr><td>" . $row['f_name'] . " " . $row['l_name'] . "</td>\n");//name column
			echo("<td>" . $row['living_area'] . "</td>\n");//living area column
			echo ("<td>" . $row['team_name'] . "</td>\n");
			if(isset($_POST['pics']))//show pictures if set to show them.
				echo('<td><img src="' . $row['picture_location'] . '" alt="' . $row['l_name'] . '" width="200px" /></td></tr>');
		}
	?>
</table>
<h1 class="table_title">Zombies</h1>
<table class="sortable">
	<tr>
		<th>Player Name</th>
		<th>Living Area</th>
		<?php 
			if(isset($_POST['pics']))
				echo '<th>Picture</th>';
		?>
		<th>Starve Time</th>
		<th>Kills</th>
	</tr>
	<?php
		$q = "select * from Game";//select all game info
		$result_game = mysqli_query($con, $q) or die ("Error with query" . mysqli_error($con));
		$game_info = mysqli_fetch_array($result_game);//get game info
		
		$q = "select f_name, l_name, living_area, picture_location, pid from Player where status=2";
		$result = mysqli_query($con, $q) or die ("error running query" . mysqli_error($con));
		$time_left = 1;
		while($row = mysqli_fetch_array($result))
		{
			$q = "select last_feed from Death_timer where pid = " . $row['pid'];//get feed time
			$result_time = mysqli_query($con, $q) or die ("Error getting time " . mysqli_error($con));
			$result_time = mysqli_fetch_array($result_time);
			$difference = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")) - $result_time['last_feed'];//difference between feed time and now.
			$hours = ($game_info['death_time_length'] - 1) - floor(($difference/60)/60);//get hours
			$minutes = 60 - ((floor($difference/60) - (60*floor(($difference/60)/60)))) - 1;//get minutes
			if($result_time['last_feed'] != "")
				$time_left = $hours." H ". $minutes . " M";
			else
				$time_left = "Alpha";
			
			echo("<tr><td>" . $row['f_name'] . " " . $row['l_name'] . "</td>\n");//name column
			echo("<td>" . $row['living_area'] . "</td>\n");//living area column
			if(isset($_POST['pics']))//if pictures are set to be shown print picture column
				echo('<td><img src="' . $row['picture_location'] . '" alt="' . $row['l_name'] . '" width="200px" /></td>');
			$q = ("select * from Tagged where taggedby_pid = " . $row['pid']);
			$kill_count_result = mysqli_query($con, $q) or die ("error getting kills " . mysqli_error($con));
			echo('<td>' . $time_left . '</td><td>' . mysqli_num_rows($kill_count_result) . ' kills</td></tr>');
		}
	?>
</table>
<h1 class="table_title">Dead</h1>
<table class="sortable">
	<tr>
		<th>Player Name</th>
		<th>Living Area</th>
	</tr>
	<?php
		$q = "select f_name, l_name, living_area, picture_location from Player where status=1";
		$result = mysqli_query($con, $q) or die ("error running query" . mysqli_error($con));
		
		while($row = mysqli_fetch_array($result))
		{
			echo("<tr><td>" . $row['f_name'] . " " . $row['l_name'] . "</td>\n");
			echo("<td>" . $row['living_area'] . "</td>\n");
		}
	?>
</table>
<div style="text-align: center; padding: 10px;">
	<form action="index.php?page=stats" method="post">
		<input type="submit" name="pics" value="Show Pictures" />
	</form>
	<p>View Map of kill locations <a href="index.php?page=kill_location">here.</a></p>
	Also view who attended the mission <a href="index.php?page=mission_attendance">here.</a>
</div>