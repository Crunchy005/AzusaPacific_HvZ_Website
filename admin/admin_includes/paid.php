<script>
	$(document).ready(function() {
		$("input:radio").click(function(){
			var email = $(this).attr('id');
			var element = $("span[id='"+email+"']");
			$.post("AJAX_functions/paid_ajax.php", 
			{
				p_email:email,
			},
			function(data){
				if($.trim(data) != "false")
				{
					element.hide();
				}
				else
				{
					alert("Player not updated.");
				}
			});
		});
	});
</script>

<?php
	if(isset($_POST['delete']))
	{
		$q = "select p_email from Player where paid = 0";
		$result = mysqli_query($con, $q) or die ("Error getting email " . mysqli_error($con));
		
		while($result_array = mysqli_fetch_assoc($result))
		{
			delete_player($result_array[p_email]);
		}
	}
?>

<div style="padding: 10px;">
	<h3>Unpaid players</h3>
	<?php
		$q = "select * from Player where paid = 0";
		$result = mysqli_query($con, $q) or die ("Error getting player info " . mysqli_error($con));
		while($result_array = mysqli_fetch_assoc($result))
		{
			echo("<span id='" . $result_array['p_email'] . "'><input type='radio' id='" . $result_array['p_email'] . "' />" . $result_array['f_name'] . " " . $result_array['l_name'] . "<br><br></span>");
		}
	?>
	<br>
	<h2 style="color:red">Be careful with this button!  Make sure everyone who has paid is not on the list.  Anyone left on the list will be deleted.</h2>
	<form method="post" action="/admin/admin.php?page=paid">
		<input type="submit" onclick="return confirm('Are you sure?')" name="delete" value="Delete Unpaid Players" />
	</form>
</div>