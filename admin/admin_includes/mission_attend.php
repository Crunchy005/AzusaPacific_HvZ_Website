<?php
	if(!check_admin_status($_SESSION['username']))
		header('location: /index.php');
?>

<div style="padding: 10px;">
	<select id="mission_list" name="mission_id">
		<option value="0">Select Mission</option>
		<?php
			//populate mission dropdown menu
			$q = "select * from Mission";
			$result = mysqli_query($con, $q) or die ("Error getting missions " . mysqli_error($con));
			while($result_array = mysqli_fetch_assoc($result))
			{
				echo "<option value='" . $result_array['mission_id'] . "'>" . $result_array['mission_name'] . "</option>";
			}
		?>
	</select>
	<h3>Mission Attendance</h3>
	<form method="post" action="/admin/admin.php?page=mission_attend">
		<ul style="list-style-type:none;" id="player_list">
			
		</ul>
		<input type="hidden" name="mission_id" value="" />
		<input type="submit" name="submit" value="Submit" />
	</form>
</div>
<?php
	//make queries and submit mission attendance to database
	/*
	//this is now an AJAX function
	if(isset($_POST['submit']))
	{
		$n = count($_POST['player']);
		$m_id = $_POST['mission_id'];
		foreach($_POST['player'] as $pid)
		{
			//echo("insert into mission_att (pid, mission_id) values ($pid, $m_id)");
			$q = "insert into Mission_att (pid, mission_id) values ('$pid', $m_id)";
			$result = mysqli_query($con, $q);
		}
		
	}*/
?>

<script>
	$('document').ready(function(){
		$("#mission_list").click(function()
		{
			var id = $('#mission_list option:selected').val();
			$("input[type='hidden']").val(id);
			
			$.ajax({
				url: "AJAX_functions/AJAX_mission_attend.php",
				type: "GET",
				data: { mission_id: id }
			})
			.done(function( html )
			{
				$("#player_list").html(html);
			});
		});
		
		$("#player_list").on('click', '#player_attend', function(){
			var pid = $(this).val();
			var mission_id = $('#mission_list option:selected').val();
			var element = $(this);

			$.ajax({
				url: "AJAX_functions/AJAX_mission_attend.php",
				type: "GET",
				data: { mission_id: mission_id,
					pid: pid }
			})
			.done(function( html )
			{
				if(html == 1)
					element.hide();
				else
					alert("Error");
			});
		});
	});
</script>