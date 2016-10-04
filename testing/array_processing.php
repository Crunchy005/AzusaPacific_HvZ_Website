<form action="#" method="post">
	<?php
		for($i = 1; $i <= 5; $i++)
		{
			echo "Share $i<input name='shares[]' type='text' /><br>";
		}
	?>
	<input type="submit" name="submit" value="Submit" />
</form>

<?php
	$date = date("Y-m-d");
	echo $date;
	if(isset($_POST['submit']))
	{
		$shares = $_POST['shares'];
		foreach($shares as $share)
		{
			echo $share . "<br>";
		}
	}
?>