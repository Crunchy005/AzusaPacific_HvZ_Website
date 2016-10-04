<?php
	//if content is submitted update it to home file.
	if(isset($_POST['submit']))
	{
		file_put_contents('../includes/home.php', stripslashes($_POST['page_update']));//Puts the contents in the editor into the home file.
	}
?>
<script src="ckeditor/ckeditor.js"></script><!--This is the editor javascript-->
<script src="ckeditor/adapters/jquery.js"></script>
<form method="post" action="">
	<div style="padding: 10px;">
		<textarea name="page_update" rows="15">
			<?php
				include('../includes/home.php');//loads home file into editor area.
			?>
		</textarea>
		<script>
			CKEDITOR.replace( 'page_update' );//adds CKEditor to the textarea
		</script>
		<input type="submit" value="submit" name="submit" />
	</div>
</form>