<!-- <script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkJLt0pxK-grgiem6vwely_KRzoKuzFBs">
</script>
<script type="text/javascript">
	var map;
	var marker;

	function initialize()
	{
		var default_pos = new google.maps.LatLng(34.130713, -117.889227);
		var mapOptions =
		{
			zoom: 15,
			center: new google.maps.LatLng(34.130713, -117.889227),
			mapTypeId: google.maps.MapTypeId.SATELLITE
		};

		map = new google.maps.Map(document.getElementById('map-canvas'),
		mapOptions);

		marker = new google.maps.Marker({
			position: new google.maps.LatLng(34.130713, -117.889227),
			map: map,
			animation: google.maps.Animation.BOUNCE,
			draggable: true,
			title: "Drag Me."
		});
		
		<?php
			$q = "select * from map";
			$result = mysqli_query($con, $q);
			while($result_array = mysqli_fetch_assoc($result))
			{
				echo "marker". $result_array['id'] . " = new google.maps.Marker({\n position: new google.maps.LatLng" . $result_array['location'] . ",\n";
				echo "map: map\n});";
			}
		?>

		marker.setMap(map);
	}

	function addLocation()
	{
			var location = marker.getPosition();
			var xmlhttp;
			if(window.XMLHttpRequest)
			{//code for IE7+ and the others
				xmlhttp = new XMLHttpRequest();
			}
			else
			{//IE5 and IE6 code
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("GET", "/includes/add_location.php?location="+location, false);
			xmlhttp.send();
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script> -->
<div style="padding: 10px;">

<form method="post" action="index.php?page=enter_kill" onsubmit="addLocation()">
	<table>
		<tr><td>Enter Code for Tagged Player: </td><td><input name="tagged" type="text" /></td></tr>
		<?php
			//get shares allowed for the game
			$q = "select shares from Game";
			$result = mysqli_query($con, $q);
			$result_array = mysqli_fetch_assoc($result);
			//output share input fields
			for($i = 0; $i < $result_array['shares']; $i++)
			{
				echo "<tr><td>Share email " . ($i+1) . " </td><td><input name='shares[]' type='text' /></td></tr>";
			}
		?>
		<!--<tr><td>Share email 1: </td><td><input name="share1" type="text" /></td></tr>
		<tr><td>Share 2 email: </td><td><input name="share2" type="text" /></td></tr>
		<tr><td>Share 3 email: </td><td><input name="share3" type="text" /></td></tr>-->
		<tr><td><input type="submit" name="enter_kill" value="Enter" /></td></tr>
	</table>
</form>

<?php
	//if enter kill is set do stuff
	if(isset($_POST['enter_kill']))
	{
		//if tagged isn't set, they didn't enter anything
		if(!isset($_POST['tagged']))
			echo("enter a player code");
		else
		{
			//trim input and sent to function
			$tagged = trim($_POST['tagged']);
			$shares = $_POST['shares'];
			$kill_result = enter_kill($tagged, $shares);
			if($kill_result == 1)
				header("location: index.php?page=enter_location");
			else
				echo ($kill_result);
		}
	}
?>
</div>

<!-- <p>Move the bouncing marker to where you got the kill then press the enter button.</p>

<div id="map-canvas" style="height: 500px; width: 500px; float: left;
"></div> -->
