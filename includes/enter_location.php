<script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCkJLt0pxK-grgiem6vwely_KRzoKuzFBs">
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
			mapTypeId: google.maps.MapTypeId.HYBRID
			
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
			
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					alert("Location added");
					window.location.replace("index.php");
				}
			}
			
			xmlhttp.open("GET", "/includes/add_location.php?location="+location, true);
			xmlhttp.send();
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>

<p>This is the fun part.  Move the bouncing marker to where you got the kill then press the enter button. (Check out the full map anytime at the bottom of the Stats page)</p>

<div id="map-canvas" style="height: 500px; width: 500px; float: left;
"></div><br>
<button onclick="addLocation()">Enter Kill Location</button>