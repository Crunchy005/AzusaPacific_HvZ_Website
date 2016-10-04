<script>
	$(document).ready(function() {
		
		$("input:radio").click(function(){
			var email = $(this).attr('id');
			var element = $("span[id='"+email+"']");
			$.post("AJAX_functions/vaccinate.php", 
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
					alert("Player not Vaccinated.");
				}
			});
		});
		
		$("#cleanse").click(function(){
			if(confirm("Are you sure you want to cleanse?"))
			{
				$.post("AJAX_functions/cleanse.php", function(data){
					if(data == 1)
					{
						alert("The population has been cleansed!!");
						location.reload();
					}
					else
						alert("Something went wrong, Our scientists are all dead and we were unable to cleanse the earth!!");
				});
			}
		});
	});
</script>
<div style="padding: 10px;">
	<h3>Vaccinate Players</h3>
	<div id="unvaccinated"></div>
	<?php
		$q = "select * from Player where status = 3 and vaccinated = 0 order by f_name ASC";
		$result = mysqli_query($con, $q) or die ("Error getting player info " . mysqli_error($con));
		while($result_array = mysqli_fetch_assoc($result))
		{
			echo("<span id='" . $result_array['p_email'] . "'><input type='radio' id='" . $result_array['p_email'] . "' />" . $result_array['f_name'] . " " . $result_array['l_name'] . "<br><br></span>");
		}
	?>
	<br><br>
	<button id="cleanse">Cleanse the world!!</button>
</div>