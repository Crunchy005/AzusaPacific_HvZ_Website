 <?php 
 	//Variables for connecting to your database.
    //These variable values come from your hosting account.
    $hostname = "localhost";
    $username = "APU_HVZ";
    $dbname = "APUHVZ";

    //These variable values need to be changed by you before deploying
    $password = "Zombonie1!";
        
    //Connecting to your database
    $con = mysqli_connect($hostname, $username, $password) OR DIE ("Unable to 
    connect to database! Please try again later.");
    mysqli_select_db($con, $dbname);
    
    $emailHeader = 'X-Mailer: PHP/' . phpversion() . '\r\n' .
					'Content-Type: text/html; charset=ISO-8859-1\r\n' . '\r\n' .
					'Return-Path: apu.hvz@gmail.com' . '\r\n' . 
					'From: APU HVZ <noreply@apuhvz.com>' . '\r\n' . 
					'Reply-To: apu.hvz@gmail.com' . '\r\n' . 
					'Organization: Azusa Pacific university' . '\r\n' . 
					'MIME-Version: 1.0'.'\r\n\r\n';
					
	//require_once("Mail.php");

?>