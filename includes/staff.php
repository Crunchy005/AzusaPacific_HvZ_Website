<table style="min-width: 10%;float: left; text-align: left;width: 70%;">
	<th><h1>Meet your staff</h1></th>
	<?php
		$q = "select picture_location, f_name, l_name from Player where status=4";
		$result = mysqli_query($con, $q) or die ("Error running query" . mysqli_error($con));
		while($result_array = mysqli_fetch_array($result))
		{
			echo("<tr>");
			echo("<td><img name='staff picture' width='250px' src='" . $result_array['picture_location'] . "' /></td>");
			echo("<td>" . $result_array['f_name'] . " " . $result_array['l_name'] . "</td></tr>");
		}
	?>
</table>