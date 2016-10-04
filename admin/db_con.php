 <?php 
 	//Variables for connecting to your database.
    //These variable values come from your hosting account.
    $hostname = "localhost";
    $username = "APU_HVZ";
    $dbname = "APUHVZ";

    //These variable values need to be changed by you before deploying
    $password = "Zombonie1!";
    
	//Connecting to your database
	$con = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($con, $dbname);
	if(mysqli_connect_error())
	{
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
?>