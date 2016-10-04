<?php
	session_start();
	ob_start("ob_gzhandler");
	include_once('./includes/header.php');
	require_once('functions.php');
	$con;
	$enter_kill = false;
	$admin = false;
	$profile = false;
	if(isset($_POST['logout']))
	{
		$_SESSION = array();
		session_destroy();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Favicon stuff start -->
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/favicons/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/favicons/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/favicons/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/favicons/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/images/favicons/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/images/favicons/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/images/favicons/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/favicons/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="/images/favicons/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="/images/favicons/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="/images/favicons/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="/images/favicons/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="/images/favicons/mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="/images/favicons/mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="/images/favicons/mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="/images/favicons/mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="/images/favicons/mstile-310x310.png" />
		<!-- Favicon stuff end -->
		
		<meta name="viewport" content="width=device-width, initial-scale=.5, maximum-scale=0.8">
		<meta name="description" content="APU HVZ is a Humans vs. Zombies game played on the APU University campus.  This website keeps track of the game and players currently involved with the game.  It is student run and created." />
		
		<title>APU HVZ <?php echo(date("Y")); ?></title>
		
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<script src="jquery-1.11.1.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		<?php
			$q = sprintf("select * from Player where p_email='%s'", $_SESSION['username']);//SQL query to check for admin status.
			$result = mysqli_query($con, $q) or die ('Unable to run query:' . mysqli_error($con));
			$array = mysqli_fetch_array($result);//fetch the array
			if($array['admin'] == 1)//check the admin value for the user that was grabbed
				$admin = true;//admin link flag

			if(isset($_SESSION['username']))//if logged in set profile/enter kill link flag
			{
				$profile = true;
				if($array['status'] == 2 || $array['status'] == 5)
					$enter_kill = true;
			}
			//echo(rand());
		?>
	</head>
	<body>
		<div id="container">
			<?php
				include 'includes/nav.php';
			?>
			<div id="login">
				<img src="images/Logo.png" width="300px" alt="Logo 1" /><br />
				<?php
					//checks the login status

					if (isset($_POST['uname']))
					{
						$login_status = check_login($_POST['uname'], $_POST['pass']);
						if($login_status == ' ')
							header("location:index.php");
						else
							echo("<span style='color:red;margin:auto;'>".$login_status."</span>");
					}

					if (isset($_SESSION['username']))
					{
						$q = sprintf("select f_name, l_name from Player where p_email='%s'", $_SESSION['username']);
						$result = mysqli_query($con, $q) or die ('Unable to run query:'.mysqli_error($con));
						$array = mysqli_fetch_array($result);
						$name = $array['f_name'] . " " . $array['l_name'];
						echo('Welcome ' . $name . ', and happy huntings!');
						echo('<form method="POST" action="index.php"><input type="submit" name="logout" value="Logout" /></form>');
					}
					else
					{
						include('includes/login.php');
					}
				?>
			</div>
			
			<div id = "page_content">
				<?php
					switch($_GET['page'])
					{
						case 'rules':
							include('includes/rules.php');
							break;
						case 'staff':
							include('includes/staff.php');
							break;
						case 'stats':
							if (isset($_SESSION['username']))
								include('includes/stats.php');
							else
								echo('<p>You must be logged in to view this page</p>');
							break;
						case 'signup':
							include('includes/sign_up.php');
							break;
						case 'profile':
							include('includes/profile.php');
							break;
						case 'activate':
							include('includes/activate.php');
							break;
						case 'enter_kill':
							include('includes/enter_kill.php');
							break;
						case 'enter_location':
							include('includes/enter_location.php');
							break;
						case 'kill_location':
							include('includes/kill_locations.php');
							break;
						case 'mission_attendance':
							include('includes/view_mission_attendance.php');
							break;
						case 'contact_us':
							include('includes/contact_us.php');
							break;
						default:
							echo("<div id='home_content'>");
							include('includes/home.php');
							echo("</div>");
					}
				?>
			</div>
		</div>
		<?php include("includes/footer.php"); ?>
	</body>
</html>
<?php
	ob_flush();
?>
