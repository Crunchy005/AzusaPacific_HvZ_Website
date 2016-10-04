<?php
	session_start();
	ob_start();
	
	if(!check_admin_status($_SESSION['username']))
		header("Location: /index.php");
?>
<div style="padding: 10px;">
	<script>
		var xmlhttp;
		if(window.XMLHttpRequest)
		{	//code for IE7+ and the others
			xmlhttp = new XMLHttpRequest();
		}
		else
		{//IE5 and IE6 code
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}

		function add_mod()
		{
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					
					/*document.getElementById("mod_add_result").innerHTML=xmlhttp.responseText;*/
					alert(xmlhttp.responseText);
					document.location.reload(true)
				}
			}
			
			//calls php function
			var email = document.getElementById("MOD_ADD").value;
			xmlhttp.open("GET", "AJAX_functions/add_mod.php?email="+email+"&type=mod", true);
			xmlhttp.send();
		}
		
		function add_admin()
		{
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					/*document.getElementById("admin_add_result").innerHTML=xmlhttp.responseText;*/
					alert(xmlhttp.responseText);
					document.location.reload(true)
				}
			}
			
			//calls php function
			var email = document.getElementById("ADMIN_ADD").value;
			xmlhttp.open("GET", "AJAX_functions/add_mod.php?email="+email+"&type=admin", true);
			xmlhttp.send();
		}
		
		function remove_admin()
		{
			var email = document.getElementById("REMOVE").value;
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("remove_result").innerHTML=xmlhttp.responseText;
					document.getElementById(email).style.display = "none";
				}
			}
			
			//calls php function
			xmlhttp.open("GET", "AJAX_functions/add_mod.php?email="+email+"&type=remove", true);
			xmlhttp.send();
		}
	</script>
		Add a Moderator to the game: <input type="text" placeholder="MOD Email" id="MOD_ADD" /><br>
		<button onclick="add_mod()">Add Mod</button><br>
		<span id="mod_add_result"></span><br>
		
		Add an Admin to the game: <input type="text" placeholder="Admin Email" id="ADMIN_ADD" /><br>
		<button onclick="add_admin()">Add Admin</button><br>
		<span id="admin_add_result"></span><br>
		
		Remove Mod/Admin from the Game: <input type="text" placeholder="Email" id="REMOVE" /><br>
		<button onclick="remove_admin()">Remove Admin</button><br>
		<span id="remove_result"></span><br>
		<table>
			Current Mods: <Br>
			<?php
				$q = "select f_name, l_name, p_email, admin from Player where status = 4 or admin = 1";//grab all mods/admins from database
				$result = mysqli_query($con, $q) or die ("Error getting Mods: " . mysqli_error($con));
				while($result_array = mysqli_fetch_assoc($result))
				{
					echo "<tr id='" . $result_array['p_email'] . "'><td>" . $result_array['f_name'] . "</td><td>" . $result_array['l_name']
					 . "</td><td style='border-left: 1px solid black; padding: 5px;'>"
					 . $result_array['p_email'] . "<td style='border-left: 1px solid black; padding: 5px;'> Admin: " . $result_array['admin'] . "</tr>";
				}
			?>
		</table>
</div>
<?php
	ob_flush();
?>
