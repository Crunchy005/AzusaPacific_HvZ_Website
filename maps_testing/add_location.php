<?php
  session_start();
  if(isset($_SESSION['username']))
  {
    $location = $_GET['location'];//pull from get array

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

    //make query to input location to DB
    //$q = "insert into map (location) values ('".$location."')";
    $q = "select * from map";
    //$q = "update Player set status = 3 where p_email='bcorn11@apu.edu'";
    $result = mysql_query($q, $con) or die ('error with query ' . mysql_error());
    while($result_array = mysql_fetch_array($result))
    {
      print_r($result_array);
      echo '<br>';
    }

    if($result)
      echo('');
    else
      echo('error');
  }
?>
