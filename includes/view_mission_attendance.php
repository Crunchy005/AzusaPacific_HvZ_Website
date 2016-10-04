<div id="stats" style="display: inline-block;">
		<h1>Mission Attendance<br><hr /></h1>
</div>
<select id='mission_list' name="mission_id">
	<option value="0">Select a Mission</option>
	<?php
		$q = "select * from Mission order by mission_id";
		$result = mysqli_query($con, $q) or die ("Error getting missions " . mysqli_error($con));
		while($result_array = mysqli_fetch_assoc($result))
		{
			echo "<option value='" . $result_array['mission_id'] . "'>" . $result_array['mission_name'] . "</option>";
		}
	?>
</select>
<div style="padding: 10px;" id="attendance">
<?php
	/*
	$q = "select * from Mission";
	$result = mysqli_query($q) or die ("Error getting missions " . mysqli_error());
	if(mysqli_num_rows($result) > 0)
	{
		while($temp = mysqli_fetch_assoc($result))
		{
			$mission_array[$temp['mission_id']] = $temp;
		}
		foreach($mission_array as $array)
		{	
			echo("<table class='sortable' style='width: 70%;'>\n");
			echo("<tr><th>" . $array['mission_name'] . "</th></tr>\n");
			
			$q = "select f_name, l_name, pid from Mission join Mission_att using (mission_id) join Player p using (pid)
				 where Mission_name = '" . $array['mission_name'] . "'";
			$result = mysqli_query($q) or die ("Error getting players " . mysqli_error());
		
			if(mysqli_num_rows($result) == 0)
				echo("no results");
		
			while($temp = mysqli_fetch_array($result))
			{
				echo("<tr><td>" . $temp['f_name'] . " " . $temp['l_name'] . "</td></tr>\n");
			}
			echo("</table>\n");
		}
	}
	*/
?>
</div>
<script>
	$('document').ready(function(){
		$("#mission_list").click(function()
		{
			$.ajax({
				url: "/includes/AJAX_missions.php",
				type: "POST",
				data: { id: $('#mission_list option:selected').val() }
			})
			.done(function( html )
			{
				$('#attendance').html(html);
			});
		});
	});
</script>