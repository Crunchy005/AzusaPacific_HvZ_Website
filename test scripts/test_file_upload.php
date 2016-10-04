<form action="" method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" />
	<input type="submit" name="submit" />
</form>
<?php
	$file = $_FILES['file']['type'];
	echo($file);
?>