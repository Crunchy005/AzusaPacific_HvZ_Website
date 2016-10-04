 <?php 
 	//Variables for connecting to your database.
    //These variable values come from your hosting account.
    $hostname = "APUHvZ00219.db.7453150.hostedresource.com";
    $username = "APUHvZ00219";
    $dbname = "APUHvZ00219";

    //These variable values need to be changed by you before deploying
    $password = "Trey128!";
        
    //Connecting to your database
    $con = mysql_connect($hostname, $username, $password) OR DIE ("Unable to 
    connect to database! Please try again later.");
    mysql_select_db($dbname, $con);
    
    $q = "select last_feed from Death_timer where pid = 1";
    $result = mysql_query($q) or die ("Error getting time " . mysql_error());
    $result_array = mysql_fetch_array($result);
    $start_date = $result_array['last_feed'];
    $end_date = strtotime("now");
    
    $date = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
    
    echo strtotime("+1 day"), "\n";
    
    echo($date);
    echo("<br><br>");
    echo(strtotime("+1 day") - $date);
    $difference = strtotime("+0 day +23 hours +59 minutes") - $date;
    $hours = floor(($difference/60)/60);
    if($hours > 24)
    {
	    echo("<br><br>dead");
    }
    else
    {
	    $minutes = ($difference/60) - (60*$hours);
	    echo("<br><br>$hours $minutes");
	    echo("<br><br>");
	    echo(23 - $hours . ":");
	    echo(60 - $minutes);
	    echo(" Left");
	 }
?>