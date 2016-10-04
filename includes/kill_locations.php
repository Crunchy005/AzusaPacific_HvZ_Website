<script type="text/javascript"
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
			zoom: 16,
			center: new google.maps.LatLng(34.130713, -117.889227),
			mapTypeId: google.maps.MapTypeId.SATELLITE
		};

		map = new google.maps.Map(document.getElementById('map-canvas'),
		mapOptions);
		
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

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="map-canvas" style="height: 700px; width: 800px; float: left;
"></div><br>