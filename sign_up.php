<?php
	session_start();
	ob_start();
	require_once('functions.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>APU HVZ</title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<script src="jquery-1.11.1.min.js"></script>
		<?php 
			include('includes/header.php');
		?>
	</head>
	<body>
		<div id="container">
			<div id="nav">
				<ul>
					<li><a href="/">Humans Vs Zombies</a></li>
					<li><a href="/index.php?page=rules">Rules</a></li>
					<li><a href="/index.php?page=staff">Staff</a></li>
					<li><a href="/index.php?page=stats">Stats</a></li>
				</ul>
			</div>
			<div id="login">
				<img src="images/Logo.png" alt="Logo 1" /><br />
			</div>
			<?php
				if(get_reg_status())
					include("includes/sign_up_form.php");
				else
					echo("<h1>Registration is Closed.</h1>");
			?>
			<script>
				/* Thanks Justin for comming up with jquery email validation.
				 * This actively validates the form through jquery and checks for password matching and valid APU email address
				*/
			$(document).ready(function() {
				var test_email = false;
				var test_pass = false;
				var test_names = false;
				
				$("input[type='submit']").attr('disabled', 'disabled'); //this disables submit
				var submit = "invalid";//set submit flag to invalid by default
				
				//on keyup for all input fields
				$("input").blur(function(){
					if($(this).attr('name') == "player_email")
						test_email = check_email();//calles check_email() and stores return value
					if($(this).attr('name') == "player_pass" || $(this).attr('name') == "player_pass_conf")
					{
						test_pass = check_pass();//calls check_pass() and stores return value
						test_names = check_names();//calls check_names() and stores return value
						test_email = check_email();//calles check_email() and stores return value
					}
					if($(this).attr('name') == "player_first" || $(this).attr('name') == "player_last")
						test_names = check_names();//calls check_names() and stores return value
					
					//checks values of the functions called previously, if all form fields are valid this will be true
					if(test_email && test_pass && test_names)
					{
						$("input[type='submit']").removeAttr('disabled');
					}
					else
					{
						$("input[type='submit']").attr('disabled', 'disabled');
					}
				});
				
				$("input[type='submit']").click(function(){
					test_email = check_email();//calles check_email() and stores return value
					test_pass = check_pass();//calls check_pass() and stores return value
					test_names = check_names();//calls check_names() and stores return value
					
					//checks values of the functions called previously, if all form fields are valid this will be true
					if(test_email && test_pass && test_names)
					{
						$("input[type='submit']").removeAttr('disabled');
					}
					else
					{
						$("input[type='submit']").attr('disabled', 'disabled');
					}
				});
			});
			
			//checks that the email field has @apu.edu at the end of it
			function check_email(){
				//var test = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test($("[name='player_email']").val());  //test holds boolean value for regular expression
				var test = /^\w+(@apu\.edu)$/i.test($("[name='player_email']").val());
				if(test)
				{
					$("[name='player_email']").css("border-left", "4px solid green");
					return true;
				}
				else
				{
					$("[name='player_email']").css("border-left", "4px solid red");
					return false;
				}
			}
			
			//checks to make sure the two password fields are the same
			function check_pass(){
				
				var test = /^(?=.*[^a-zA-Z])(?=.*[a-z])(?=.*[A-Z])\S{8,}$/.test($("[name='player_pass']").val());
				
				if(!test)
				{
					$("#pass_valid").text("Your password must have 8 characters, at least 1 uppercase, 1 lowercase, and 1 number");
					return false;
				}
				else
					$("#pass_valid").text("");
				
				if(($("[name='player_pass']").val() == $("[name='player_pass_conf']").val()))
				{
					$("#pass_match").text(" ");
					return true;
				}
				else
				{
					$("#pass_match").text("Passwords do not match");
					return false;
				}
			}
			
			//checks that the name fields are not empty
			function check_names(){
				if(($("[name='player_first']").val().length > 0) && ($("[name='player_last']").val().length > 0))
					return true;
				else
					return false;
			}
			
			</script>
		</div>
		<div id="footer">
			<?php
				include("includes/footer.php");
			?>
		</div>
	</body>
</html>
<?php
	ob_flush();
?>