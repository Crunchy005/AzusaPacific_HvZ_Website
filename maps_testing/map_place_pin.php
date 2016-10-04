<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { height: 100% }
    </style>
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
			    zoom: 18,
			    center: new google.maps.LatLng(34.130713, -117.889227),
			    mapTypeId: google.maps.MapTypeId.STREET
			};

			map = new google.maps.Map(document.getElementById('map-canvas'),
			mapOptions);

			marker = new google.maps.Marker({
				position: default_pos,
				map: map,
				animation: google.maps.Animation.BOUNCE,
				draggable: true,
				title: "Drag Me."
			});

			marker.setMap(map);
		}

		function add_location()
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
            if(xmlhtpp.responseText() == "")
            {
              alert('location added');
            }
            else
                alert('error adding location ' + xmlhttp.responseText());
          }
        }
        xmlhttp.open("GET", "add_location.php?location="+location, true);
        xmlhttp.send();
        //alert('info sent');
		}

		google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
    <div id="map-canvas" style="height: 80%;"></div>
    <button onclick="add_location()">click me!</button>
  </body>
</html>
