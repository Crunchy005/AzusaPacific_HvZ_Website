<?php
	session_start();
	ob_start("ob_gzhandler");//the gz handler I think was an email fix, and ob_start is so redirects don't get messed up.  Ob_flush() is on bottom
	require_once('admin_functions.php');//including the functions here.
	require_once('db_con.php');//connection to the database!
	if(!check_admin_status($_SESSION['username']))//redirect to home page if and admin is not signed in and trying to get to this page. Yay security.
		header("Location: /index.php");
?>
<!DOCTYPE HTML>
<script src="../jquery-1.11.1.min.js" charset="utf-8"></script>
<html>
	<head>
		<title>HVZ Admin</title>
		<link rel="stylesheet" type="text/css" href="admin_style.css"/>
	</head>
	<body>
		<div id="wrapper">
			<div id='admin_left'>
				<ul id='settings'>
					<li><a href='/admin/admin.php'>Main Settings</a></li>
					<li><a href='?page=mass_email'>Mass Emails</a></li>
					<li><a href='?page=mods_admin'>Mod/Admin settings</a></li>
					<li><a href='?page=front_page_edit'>Front Page</a></li>
					<li><a href='?page=manage_missions'>Manage Missions</a></li>
					<li><a href='?page=profile_admin'>Player Profile Controls</a></li>
					<li><a href='?page=vaccinations'>Vaccination</a></li>
					<li><a href='?page=mission_attend'>Mission Attendance</a></li>
					<li><a href='?page=paid'>Paid</a></li>
					<li><a href='/'>Home</a></li>
				</ul>
			</div>
			<div id='admin_right'>
				<?php
					//includes file based on current page set in URL
					$page = $_GET['page'];
					switch($page)
					{
						case 'mass_email':
							include('admin_includes/mass_email.php');
							break;
						case 'mods_admin':
							include('admin_includes/mods_admin.php');
							break;
						case 'front_page_edit':
							include('admin_includes/front_page_edit.php');
							break;
						case 'manage_missions':
							include('admin_includes/manage_missions.php');
							break;
						case 'profile_admin':
							include('admin_includes/profile_admin.php');
							break;
						case 'vaccinations':
							include('admin_includes/vaccinations.php');
							break;
						case 'mission_attend':
							include('admin_includes/mission_attend.php');
							break;
						case 'paid':
							include('admin_includes/paid.php');
							break;
						default:
							include('admin_includes/main_settings.php');
					}
				?>
			</div>
		</div>
	</body>
</html>
<?php
	ob_flush();
?>